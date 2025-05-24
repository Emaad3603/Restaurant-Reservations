<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\MealType;
use App\Models\MealTypeTranslation;
use App\Models\RestaurantMealType;
use App\Models\MenuCategory;
use App\Models\RestaurantPricingTime;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
        
        $hotel = Hotel::find(session('hotel_id'));
        $roomNumber = session('room_number');
        
        // Get restaurants based on hotel's restriction settings
        $restaurants = Restaurant::where('active', 1)
            ->where(function($query) use ($hotel) {
                if ($hotel->restricted_restaurants === 1) {
                    // Only show restaurants from this hotel
                    $query->where('hotel_id', $hotel->hotel_id);
                }
            })
            ->get();
            
        // Get all meal types with translations
        $mealTypes = MealType::with(['translations' => function($query) {
                $query->where('language_code', 'en');
            }])
            ->where('company_id', $hotel->company_id)
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
        
        // Get remaining free reservations
        $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
        
        return view('restaurants.index', compact('restaurants', 'mealTypes', 'remainingFreeReservations', 'hotel', 'roomNumber'));
    }
    
    /**
     * Filter restaurants by meal type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $request->validate([
            'meal_type_id' => 'required|exists:meal_types,meal_types_id'
        ]);
        
        $hotel = Hotel::find(session('hotel_id'));
        $roomNumber = session('room_number');
        
        // Get restaurants that serve the selected meal type
        $restaurants = $hotel->getAvailableRestaurants()
            ->filter(function($restaurant) use ($request) {
                return $restaurant->mealTypes()
                    ->where('meal_types_id', $request->meal_type_id)
                    ->exists();
            });
            
        // Get remaining free reservations
        $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
        
        return view('restaurants.index', compact('restaurants', 'remainingFreeReservations'));
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
        $hotel = Hotel::find(session('hotel_id'));
        
        // Check if restaurant is restricted
        if ($hotel->isRestaurantRestricted($restaurantId)) {
            return redirect()->route('restaurants.index')
                ->with('error', 'This restaurant is not available for your hotel.');
        }
        
        // Get restaurant translation
        $translation = $restaurant->getTranslation('en');
        
        // Get menu categories with translations
        $menuCategories = MenuCategory::with(['translations' => function($query) {
                $query->where('language_code', 'en');
            }])
            ->where('company_id', $restaurant->company_id)
            ->get();
            
        // Get all menus for the company
        $menus = Menu::where('company_id', $restaurant->company_id)->get();
            
        return view('restaurants.menu', compact('restaurant', 'translation', 'menuCategories', 'menus'));
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
        $hotel = Hotel::find(session('hotel_id'));
        $roomNumber = session('room_number');
        $guestReservationId = session('guest_reservation_id');
        
        // Check if restaurant is restricted
        if ($hotel->isRestaurantRestricted($restaurantId)) {
            return redirect()->route('restaurants.index')
                ->with('error', 'This restaurant is not available for your hotel.');
        }
        
        // Get available time slots
        $pricingTimes = RestaurantPricingTime::where('restaurant_id', $restaurantId)
            ->where('hotel_id', $hotel->hotels_id)
            ->where(function($query) {
                $query->whereNull('year')
                    ->orWhere('year', '>=', date('Y'));
            })
            ->where(function($query) {
                $query->whereNull('month')
                    ->orWhere('month', '>=', date('m'));
            })
            ->where(function($query) {
                $query->whereNull('day')
                    ->orWhere('day', '>=', date('d'));
            })
            ->orderBy('time')
            ->get();
        
        // Get remaining free reservations (only if always_paid_free is null)
        $remainingFreeReservations = null;
        if (is_null($restaurant->always_paid_free)) {
            $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
        }
        
        // Get guests from the room
        $guests = DB::table('guest_details')
            ->join('guest_reservations', 'guest_details.guest_reservations_id', '=', 'guest_reservations.guest_reservations_id')
            ->where('guest_reservations.guest_reservations_id', $guestReservationId)
            ->where('guest_reservations.room_number', $roomNumber)
            ->get();
        
        return view('restaurants.reserve', compact('restaurant', 'pricingTimes', 'remainingFreeReservations', 'guests', 'hotel'));
    }

    /**
     * Get available times for a specific date
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimes(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,restaurants_id',
            'date' => 'required|date|after_or_equal:today'
        ]);

        $date = Carbon::parse($request->date);
        
        $times = RestaurantPricingTime::where('restaurant_id', $request->restaurant_id)
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
            ->get(['time']);

        return response()->json([
            'success' => true,
            'times' => $times
        ]);
    }
}
