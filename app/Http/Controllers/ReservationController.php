<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\MealType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\GuestReservation;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,restaurants_id',
            'meal_type_id' => 'required|exists:meal_types,meal_types_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'customer_request' => 'nullable|string|max:500',
        ]);
        
        // Check if guest reservation exists
        if (!session('guest_reservation_id')) {
            return redirect()->route('hotels.index')
                ->with('error', 'Please start the reservation process again.');
        }
        
        // --- Hotel-based validation ---
        $guestReservation = GuestReservation::find(session('guest_reservation_id'));
        if (!$guestReservation) {
            return back()->withErrors(['date' => 'Guest reservation not found.']);
        }
        $hotel = Hotel::find($guestReservation->hotel_id);
        if (!$hotel) {
            return back()->withErrors(['date' => 'Hotel not found.']);
        }

        // Check verification type
        if ($hotel->verification_type == 0) {
            if (!session('guest_name')) {
                return redirect()->route('guest.create')->with('error', 'Please verify your birthdate.');
            }
        } else if ($hotel->verification_type == 1) {
            // Validate using departure_date
            if (!$guestReservation->departure_date) {
                return back()->withErrors(['date' => 'Departure date is required for verification.']);
            }
        }

        $reservationDate = \Carbon\Carbon::parse($request->input('date'));
        $arrivalDate = \Carbon\Carbon::parse($guestReservation->arrival_date);
        $departureDate = \Carbon\Carbon::parse($guestReservation->departure_date);
        // plus_days_adjust: must be after arrival + plus_days_adjust
        $earliestReservation = $arrivalDate->copy()->addDays($hotel->plus_days_adjust);
        if ($reservationDate->lt($earliestReservation)) {
            return back()->withErrors(['date' => 'You can only make a reservation after ' . $hotel->plus_days_adjust . ' days from your arrival.']);
        }
        // minus_days_adjust: must be before departure - minus_days_adjust
        $latestReservation = $departureDate->copy()->subDays($hotel->minus_days_adjust);
        if ($reservationDate->gt($latestReservation)) {
            return back()->withErrors(['date' => 'You cannot make a reservation within ' . $hotel->minus_days_adjust . ' days before your departure.']);
        }
        // free_count: check how many reservations the guest has made
        $reservationCount = Reservation::where('guest_reservations_id', $guestReservation->guest_reservations_id)->count();
        $paidReservation = false;
        // Board type + hotel free count logic
        if ($guestReservation->board_type && $guestReservation->hotel_id == $hotel->hotel_id) {
            $boardRule = DB::table('board_type_rules')
                ->where('company_id', $hotel->company_id)
                ->where('hotel_id', $hotel->hotel_id)
                ->where('board_type_rules_id', $guestReservation->board_type)
                ->first();
            $freeCount = $boardRule ? $boardRule->free_count : 0;
            $hotelFreeCount = $hotel->free_count ?? 0;
            $maxFree = $freeCount + $hotelFreeCount;
            \Log::info('ReservationBoardTypeCheck', [
                'guestBoardType' => $guestReservation->board_type,
                'boardRule' => $boardRule,
                'freeCount' => $freeCount,
                'hotelFreeCount' => $hotelFreeCount,
                'maxFree' => $maxFree,
                'reservationCount' => $reservationCount
            ]);
            if ($reservationCount >= $maxFree) {
                $paidReservation = true;
                session(['paid_reservation' => true]);
                session()->flash('paid_message', 'You have exceeded your free reservation limit (board type + hotel). This reservation will be paid.');
            } else {
                $paidReservation = false;
                session(['paid_reservation' => false]);
            }
        } else if ($reservationCount >= $hotel->free_count) {
            // Guest has exceeded free_count, set paid reservation flag
            $paidReservation = true;
            session(['paid_reservation' => true]);
            session()->flash('paid_message', 'You have exceeded your free reservation limit. This reservation will be paid.');
        } else {
            $paidReservation = false;
            session(['paid_reservation' => false]);
        }
        // --- End hotel-based validation ---
        
        // Translation option for menu
        if ($request->has('menu_translation')) {
            session(['menu_translation' => $request->input('menu_translation')]);
        }
        
        // Create the reservation
        $reservation = new Reservation();
        $reservation->guest_reservations_id = session('guest_reservation_id');
        $reservation->room_number = session('room_number');
        $reservation->restaurant_id = $request->restaurant_id;
        $reservation->day = $request->date;
        $reservation->time = $request->time;
        $reservation->pax = session('number_of_guests');
        $reservation->canceled = 0;
        $reservation->ended = 0;
        $reservation->company_id = 1; // Default company ID, adjust as needed
        $reservation->qrcode = Str::random(20);
        $reservation->created_by = 'system';
        $reservation->meal_types_id = $request->meal_type_id;
        $reservation->time_zone = 'UTC';
        $reservation->save();
        
        // Store reservation info in session
        session(['reservation_id' => $reservation->reservations_id]);
        
        // Redirect to confirmation page
        return redirect()->route('reservations.confirm', $reservation->reservations_id);
    }
    
    /**
     * Display the specified reservation confirmation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $reservation = Reservation::with(['restaurant', 'mealType', 'guestReservation.guestDetails'])
            ->findOrFail($id);
            
        // Make sure this is the current user's reservation
        if (session('reservation_id') != $id) {
            return redirect()->route('hotels.index')
                ->with('error', 'Invalid reservation.');
        }
        
        return view('reservations.confirm', compact('reservation'));
    }
    
    /**
     * Generate and download a receipt for the reservation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function printReceipt($id)
    {
        $reservation = Reservation::with(['restaurant', 'mealType', 'guestReservation.guestDetails'])
            ->findOrFail($id);
            
        // Make sure this is the current user's reservation
        if (session('reservation_id') != $id) {
            return redirect()->route('hotels.index')
                ->with('error', 'Invalid reservation.');
        }
        
        // Generate PDF receipt
        $pdf = Pdf::loadView('reservations.receipt', compact('reservation'));
        
        return $pdf->download('reservation_receipt_' . $reservation->qrcode . '.pdf');
    }
}
