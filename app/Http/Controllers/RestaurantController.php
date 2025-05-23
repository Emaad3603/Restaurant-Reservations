<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MealType;
use App\Models\MealTypeTranslation;
use App\Models\RestaurantMealType;
use App\Models\MenuCategory;
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
        
        // Get all meal types with translations
        $mealTypes = MealType::with(['translations' => function($query) {
                $query->where('language_code', 'en'); // Default to English
            }])
            ->where('company_id', 1) // Default company ID, adjust as needed
            ->get();
        
        // For each meal type, get its translated name
        foreach ($mealTypes as $mealType) {
            $translation = $mealType->getTranslation('en');
            if ($translation) {
                $mealType->translated_name = $translation->name;
            } else {
                $mealType->translated_name = $mealType->name ?? 'Unknown';
            }
        }
        
        return view('restaurants.index', compact('restaurants', 'mealTypes'));
    }
    
    /**
     * Filter restaurants by meal type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $mealTypeId = $request->input('meal_type_id');
        $hotelId = $request->input('hotel_id');
        
        // Get restaurants that offer the selected meal type
        $restaurantIds = RestaurantMealType::where('meal_type_id', $mealTypeId)
            ->pluck('restaurant_id')
            ->toArray();
            
        // If no restaurants are found for this meal type, return all active restaurants in this hotel
        if (empty($restaurantIds)) {
            $restaurantIds = Restaurant::where('hotel_id', $hotelId)
                ->where('active', 1)
                ->pluck('restaurants_id')
                ->toArray();
        }
        
        return response()->json([
            'success' => true,
            'restaurants' => $restaurantIds
        ]);
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
        $translation = $restaurant->getTranslation('en');

        // Fetch the Breakfast Menu for this restaurant/company
        $menu = \App\Models\Menu::where('company_id', $restaurant->company_id)
            ->where('label', 'Breakfast Menu')
            ->first();

        $menuCategories = \App\Models\MenuCategory::with([
            'subcategories.items'
        ])->where('company_id', $restaurant->company_id)->get();

        return view('restaurants.menu', compact('restaurant', 'translation', 'menuCategories', 'menu'));
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
        
        // Use the restaurant's company_id
        $mealTypes = MealType::with(['translations' => function($query) {
                $query->where('language_code', 'en');
            }])
            ->where('company_id', $restaurant->company_id)
            ->get();
        
        // For each meal type, get its translated name
        foreach ($mealTypes as $mealType) {
            $translation = $mealType->getTranslation('en');
            if ($translation) {
                $mealType->translated_name = $translation->name;
            } else {
                $mealType->translated_name = $mealType->name ?? 'Unknown';
            }
        }
        
        return view('restaurants.reserve', compact('restaurant', 'mealTypes'));
    }
}
