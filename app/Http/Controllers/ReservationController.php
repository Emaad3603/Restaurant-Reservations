<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\MealType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'meal_type_id' => 'required|exists:meal_types,meal_type_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'customer_request' => 'nullable|string|max:500',
        ]);
        
        // Check if guest reservation exists
        if (!session('guest_reservation_id')) {
            return redirect()->route('hotels.index')
                ->with('error', 'Please start the reservation process again.');
        }
        
        // Combine date and time
        $dateTime = Carbon::parse($request->date . ' ' . $request->time);
        
        // Create the reservation
        $reservation = new Reservation();
        $reservation->date_time = $dateTime;
        $reservation->guest_reservations_id = session('guest_reservation_id');
        $reservation->restaurant_id = $request->restaurant_id;
        $reservation->meal_type_id = $request->meal_type_id;
        $reservation->pax = session('number_of_guests');
        $reservation->status = 'confirmed';
        $reservation->customer_request = $request->customer_request;
        $reservation->company_id = 1; // Default company ID, adjust as needed
        $reservation->token = Str::random(20);
        $reservation->confirmation_code = strtoupper(Str::random(6));
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
        
        return $pdf->download('reservation_receipt_' . $reservation->confirmation_code . '.pdf');
    }
}
