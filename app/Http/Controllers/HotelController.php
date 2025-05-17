<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the active hotels.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotels = Hotel::where('active', 1)->get();
        return view('hotels.index', compact('hotels'));
    }
    
    /**
     * Validate the room number for the specified hotel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $hotelId
     * @return \Illuminate\Http\Response
     */
    public function validateRoom(Request $request, $hotelId)
    {
        $request->validate([
            'room_number' => 'required|string|max:20',
        ]);
        
        $hotel = Hotel::findOrFail($hotelId);
        
        // Store the hotel and room information in the session
        session([
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $hotel->name,
            'room_number' => $request->room_number
        ]);
        
        // Redirect to the guest information page
        return redirect()->route('guest.create');
    }
}
