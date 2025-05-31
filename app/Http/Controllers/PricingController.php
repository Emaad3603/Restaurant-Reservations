<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\RestaurantPricingTime;
use App\Models\RestaurantPricingTimeDiscount;
use App\Models\RestaurantPricingTimeTax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PricingController extends Controller
{
    /**
     * Get available time slots for a restaurant on a specific date
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimeSlots(Request $request, $restaurantId)
    {
        try {
            $request->validate([
                'date' => 'required|date|after_or_equal:today'
            ]);
            
            // Debug log to print the entire request data
            \Log::info('Request data:', $request->all());
            
            $hotelId = Session::get('hotel_id');
            $date = Carbon::parse($request->date);
            
            \Log::info('Fetching time slots', [
                'restaurant_id' => $restaurantId,
                'hotel_id' => $hotelId,
                'date' => $date->format('Y-m-d'),
                'year' => $date->format('Y'),
                'month' => $date->format('m'),
                'day' => $date->format('d'),
                'session_data' => Session::all()
            ]);
            
            // Get all pricing times for this restaurant and hotel
            $allPricingTimes = RestaurantPricingTime::where('restaurant_id', $restaurantId)
                ->where('hotel_id', $hotelId)
                ->get();
                
            \Log::info('All pricing times found', [
                'count' => $allPricingTimes->count(),
                'times' => $allPricingTimes->toArray()
            ]);
            
            // Get time slots for the specific date
            $timeSlots = RestaurantPricingTime::where('restaurant_id', $restaurantId)
                ->where('hotel_id', $hotelId)
                ->where(function($query) use ($date) {
                    $query->where(function($q) use ($date) {
                        $q->where('year', (string)$date->year)
                          ->where('month', (string)$date->format('m'))
                          ->where('day', (string)$date->format('d'));
                    })->orWhere(function($q) {
                        $q->whereNull('year')
                          ->whereNull('month')
                          ->whereNull('day');
                    });
                })
                ->orderBy('time')
                ->get(['time', 'meal_type', 'price']);
                
            \Log::info('Time slots found', [
                'count' => $timeSlots->count(),
                'slots' => $timeSlots->toArray(),
                'query_log' => DB::getQueryLog()
            ]);
            
            return response()->json($timeSlots);
        } catch (\Exception $e) {
            \Log::error('Error fetching time slots: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch time slots'], 500);
        }
    }
    
    /**
     * Check if a time slot is available
     */
    private function isSlotAvailable($restaurant, $slot, $hotelTime)
    {
        // Check if slot is in the past
        $slotDateTime = Carbon::parse($slot->date . ' ' . $slot->start_time)
            ->setTimezone($hotelTime->timezone);
            
        if ($slotDateTime->isPast()) {
            return false;
        }
        
        // Check restaurant capacity
        $reservedCount = DB::table('reservations')
            ->where('restaurant_id', $restaurant->restaurant_id)
            ->where('date', $slot->date)
            ->where('time', $slot->start_time)
            ->where('status', '!=', 'cancelled')
            ->sum('number_of_guests');
            
        return $reservedCount < $restaurant->capacity;
    }
    
    /**
     * Calculate price for a reservation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,restaurants_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'guests' => 'required|array|min:1'
        ]);

        // Get pricing time
        $pricingTime = RestaurantPricingTime::where('restaurant_id', $request->restaurant_id)
            ->where('hotel_id', session('hotel_id'))
            ->where('time', $request->time)
            ->first();

        if (!$pricingTime) {
            return response()->json([
                'success' => false,
                'message' => 'Selected time is not available'
            ]);
        }

        // Backend validation for per_person
        if ($pricingTime->per_person == 0) {
            // All guests in the room must be included
            $guestReservationId = session('guest_reservation_id');
            $allGuests = \DB::table('guest_details')
                ->where('guest_reservations_id', $guestReservationId)
                ->pluck('guest_details_id')->toArray();
            $selectedGuests = $request->guests;
            sort($allGuests);
            $selectedSorted = $selectedGuests;
            sort($selectedSorted);
            if ($allGuests != $selectedSorted) {
                return response()->json([
                    'success' => false,
                    'message' => 'All guests in the room must be included in the reservation.'
                ]);
            }
        }

        // Get the restaurant and hotel
        $restaurant = \App\Models\Restaurant::find($request->restaurant_id);
        $hotel = \App\Models\Hotel::find(session('hotel_id'));
        $roomNumber = session('room_number');
        $guestReservationId = session('guest_reservation_id');
        $guestReservation = \App\Models\GuestReservation::find($guestReservationId);
        $guestBoardType = $guestReservation ? $guestReservation->board_type : null;
        $guestHotelId = $guestReservation ? $guestReservation->hotel_id : null;
        $companyId = $hotel ? $hotel->company_id : null;
        
        // Determine pricing logic with board type priority
        $basePrice = $pricingTime->per_person * count($request->guests);
        $currencySymbol = $pricingTime->currency->symbol ?? '$';
        $totalPrice = $basePrice;
        $status = '';
        if ($guestBoardType && $guestHotelId == $restaurant->hotel_id) {
            $boardRule = \DB::table('board_type_rules')
                ->where('company_id', $companyId)
                ->where('hotel_id', $hotel->hotel_id)
                ->where('board_type_rules_id', $guestBoardType)
                ->first();
            $freeCount = $boardRule ? $boardRule->free_count : 0;
            $hotelFreeCount = $hotel->free_count ?? 0;
            $maxFree = $freeCount + $hotelFreeCount;
            $guestReservationCount = \DB::table('reservations')
                ->where('guest_reservations_id', $guestReservationId)
                ->count();
            \Log::info('PricingBoardTypeCheck', [
                'restaurant_id' => $restaurant->restaurants_id,
                'restaurant_name' => $restaurant->name,
                'guestBoardType' => $guestBoardType,
                'boardRule' => $boardRule,
                'freeCount' => $freeCount,
                'hotelFreeCount' => $hotelFreeCount,
                'maxFree' => $maxFree,
                'guestReservationCount' => $guestReservationCount
            ]);
            if ($guestReservationCount < $maxFree) {
                $totalPrice = 0;
                $status = 'free_with_board_type';
            } else {
                $status = 'paid_after_board_type';
            }
        } elseif ($restaurant->always_paid_free === 0) {
            $totalPrice = 0;
            $status = 'always_free';
        } elseif ($restaurant->always_paid_free === 1) {
            $status = 'always_paid';
        } else {
            $remainingFreeReservations = $hotel ? $hotel->getRemainingFreeReservations($roomNumber) : 0;
            \Log::info('PricingHotelFreeCountCheck', [
                'restaurant_id' => $restaurant->restaurants_id,
                'restaurant_name' => $restaurant->name,
                'remainingFreeReservations' => $remainingFreeReservations
            ]);
            if ($remainingFreeReservations > 0) {
                $totalPrice = 0;
                $status = 'free_remaining';
            } else {
                $status = 'paid_after_free';
            }
        }
        return response()->json([
            'success' => true,
            'base_price' => $basePrice,
            'total_price' => $totalPrice,
            'currency_symbol' => $currencySymbol,
            'status' => $status
        ]);
    }
} 