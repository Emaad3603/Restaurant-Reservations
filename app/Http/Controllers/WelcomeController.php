<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Clear any existing session data when starting a new reservation
        session()->forget(['hotel_id', 'hotel_name', 'room_number', 'guest_reservation_id', 
                         'guest_name', 'number_of_guests', 'reservation_id']);
        
        // Get the first active company
        $company = Company::first();
        
        return view('welcome', compact('company'));
    }
} 