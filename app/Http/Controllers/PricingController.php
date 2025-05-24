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
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        $date = Carbon::parse($request->date);
        
        $times = RestaurantPricingTime::where('restaurant_id', $restaurantId)
            ->where('hotel_id', session('hotel_id'))
            ->where(function($query) use ($date) {
                $query->whereNull('year')
                    ->orWhere('year', $date->year);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('month')
                    ->orWhere('month', $date->month);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('day')
                    ->orWhere('day', $date->day);
            })
            ->orderBy('time')
            ->get(['time', 'meal_type']);

        return response()->json([
            'success' => true,
            'times' => $times
        ]);
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

        // Determine pricing logic based on always_paid_free
        $basePrice = $pricingTime->per_person * count($request->guests);
        $currencySymbol = $pricingTime->currency->symbol ?? '$';
        $totalPrice = $basePrice;
        $status = '';
        if ($restaurant->always_paid_free === 0) {
            // Always free
            $totalPrice = 0;
            $status = 'always_free';
        } elseif ($restaurant->always_paid_free === 1) {
            // Always paid
            $status = 'always_paid';
        } else {
            // Use hotel free_count logic (always_paid_free is null)
            $remainingFreeReservations = $hotel ? $hotel->getRemainingFreeReservations($roomNumber) : 0;
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