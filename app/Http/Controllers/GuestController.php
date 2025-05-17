<?php

namespace App\Http\Controllers;

use App\Models\GuestDetail;
use App\Models\GuestReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        
        return view('guests.create', compact('guestReservation'));
    }
    
    /**
     * Validate guest birthdate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'birth_date' => 'required|date|before:today',
        ]);
        
        // Get the guest reservation from session
        $guestReservationId = session('guest_reservation_id');
        $guestReservation = GuestReservation::with('guestDetails')
            ->findOrFail($guestReservationId);
        
        // Validate that the provided birthdate matches one of the guests
        $birthDate = Carbon::parse($request->birth_date)->format('Y-m-d');
        $validGuest = false;
        $guestName = '';
        
        foreach ($guestReservation->guestDetails as $guestDetail) {
            if ($guestDetail->birth_date == $birthDate) {
                $validGuest = true;
                $guestName = $guestDetail->guest_name;
                break;
            }
        }
        
        if (!$validGuest) {
            return back()->withErrors(['birth_date' => 'The birthdate does not match any guest in this room.'])
                ->withInput();
        }
        
        // Store guest information in the session
        session([
            'guest_name' => $guestName,
            'number_of_guests' => $guestReservation->pax
        ]);
        
        // Redirect to the restaurant selection page
        return redirect()->route('restaurants.index');
    }
}
