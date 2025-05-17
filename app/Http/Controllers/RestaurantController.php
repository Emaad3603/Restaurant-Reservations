<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MealType;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the restaurants for the selected hotel.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if hotel and guest information is selected
        if (!session('hotel_id') || !session('guest_reservation_id')) {
            return redirect()->route('hotels.index')
                ->with('error', 'Please select a hotel and verify your identity first.');
        }
        
        $hotelId = session('hotel_id');
        
        // Get all active restaurants in the hotel
        $restaurants = Restaurant::where('hotel_id', $hotelId)
            ->where('active', 1)
            ->get();
        
        // Get all meal types
        $mealTypes = MealType::where('company_id', 1) // Default company ID, adjust as needed
            ->get();
        
        return view('restaurants.index', compact('restaurants', 'mealTypes'));
    }
    
    /**
     * Display the menu for a restaurant.
     *
     * @param  int  $restaurantId
     * @return \Illuminate\Http\Response
     */
    public function showMenu($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        
        // Get restaurant translation (assuming English as default)
        $translation = $restaurant->getTranslation('en');
        
        return view('restaurants.menu', compact('restaurant', 'translation'));
    }
    
    /**
     * Show the form for making a reservation at a restaurant.
     *
     * @param  int  $restaurantId
     * @return \Illuminate\Http\Response
     */
    public function showReservationForm($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        
        // Get meal types
        $mealTypes = MealType::where('company_id', 1) // Default company ID, adjust as needed
            ->get();
        
        return view('restaurants.reserve', compact('restaurant', 'mealTypes'));
    }
}
