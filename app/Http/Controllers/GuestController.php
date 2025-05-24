<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    /**
     * Show the form for validating guest through birthdate.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if hotel and room are selected
        if (!session('hotel_id') || !session('room_number')) {
            return redirect()->route('hotels.index')
                ->with('error', 'Please select a hotel and enter your room number first.');
        }
        
        // Find the guest reservation for this room
        $guestReservation = GuestReservation::where('hotel_id', session('hotel_id'))
            ->where('room_number', session('room_number'))
            ->where('status', 'checked_in')
            ->first();
            
        if (!$guestReservation) {
            return redirect()->route('hotels.index')
                ->with('error', 'No active reservation found for this room. Please check your room number and try again.');
        }
        
        // Store the guest reservation ID in session
        session(['guest_reservation_id' => $guestReservation->guest_reservations_id]);
        
        // Get the hotel and its verification_type
        $hotel = \App\Models\Hotel::find(session('hotel_id'));
        $verificationType = $hotel ? $hotel->verification_type : 0;
        
        return view('guests.create', compact('guestReservation', 'verificationType'));
    }
    
    /**
     * Validate guest birthdate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $guestReservationId = session('guest_reservation_id');
        if (!$guestReservationId) {
            return redirect()->route('hotels.index')
                ->with('error', 'Session expired. Please start over.');
        }
        $guestReservation = GuestReservation::with('guestDetails')
            ->findOrFail($guestReservationId);
        $hotel = \App\Models\Hotel::find($guestReservation->hotel_id);
        $verificationType = $hotel ? $hotel->verification_type : 0;

        if ($verificationType == 0) {
            // Validate birthdate
            $request->validate([
                'birth_date' => 'required|date|before:today',
            ]);
            $birthDate = \Carbon\Carbon::parse($request->birth_date)->format('Y-m-d');
            $validGuest = false;
            $guestName = '';
            foreach ($guestReservation->guestDetails as $guest) {
                if ($guest->birth_date == $birthDate) {
                    $validGuest = true;
                    $guestName = $guest->guest_name;
                    break;
                }
            }
            if (!$validGuest) {
                return back()->withErrors(['birth_date' => 'The birthdate does not match any guest in this room.'])
                    ->withInput();
            }
            session(['guest_name' => $guestName]);
        } else if ($verificationType == 1) {
            // Validate departure date
            $request->validate([
                'departure_date' => 'required|date|after:today',
            ]);
            $departureDate = \Carbon\Carbon::parse($request->departure_date)->format('Y-m-d');
            if ($guestReservation->departure_date != $departureDate) {
                return back()->withErrors(['departure_date' => 'The departure date does not match the reservation.'])
                    ->withInput();
            }
            session(['guest_name' => $guestReservation->guest_name ?? 'Guest']);
        }
        // Continue to restaurant selection
        return redirect()->route('restaurants.index');
    }
}
