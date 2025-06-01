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
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Database\Eloquent\Model;

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
        if (!Request::session()->has('hotel_id') || !Request::session()->has('guest_reservation_id')) {
            return redirect()->route('hotels.index')
                ->with('error', 'Please select a hotel and verify your identity first.');
        }
        
        $hotel = Hotel::find(Request::session()->get('hotel_id'));
        $roomNumber = Request::session()->get('room_number');
        $guestReservationId = Request::session()->get('guest_reservation_id');
        $guestReservation = \App\Models\GuestReservation::find($guestReservationId);
        $guestBoardType = $guestReservation ? $guestReservation->board_type : null;
        $guestHotelId = $guestReservation ? $guestReservation->hotel_id : null;
        $companyId = $hotel->company_id;
        
        // Get restaurants based on hotel's restriction settings
        $restaurants = Restaurant::where('active', 1)
            ->get();
            
        // Filter and set status based on hotel's restricted_restaurants setting
        $restaurants = $restaurants->filter(function($restaurant) use ($hotel, $guestHotelId, $guestBoardType, $companyId) {
            // Case 1: restricted_restaurants = 1 (only show restaurants from same hotel)
            if ($hotel->restricted_restaurants === 1) {
                return $restaurant->hotel_id == $guestHotelId;
            }
            
            // Case 2: restricted_restaurants = 2 (show all but always paid for other hotels)
            if ($hotel->restricted_restaurants === 2) {
                if ($restaurant->hotel_id != $guestHotelId) {
                    $restaurant->always_paid_free = 1; // Force always paid for other hotels
                }
                return true;
            }
            
            // Case 0: restricted_restaurants = 0 (show all, use free count for pricing)
            return true;
        });
        
        // Determine free/paid status for each restaurant
        $restaurantStatuses = [];
        foreach ($restaurants as $restaurant) {
            $isSameHotel = $restaurant->hotel_id == $guestHotelId;
            $status = [
                'free_with_board_type' => false,
                'free' => false,
                'paid' => false,
                'reason' => ''
            ];
            if ($guestBoardType && $isSameHotel) {
                // Log the type and value of guestBoardType
                \Log::info('GuestBoardTypeDebug', [
                    'guestBoardType' => $guestBoardType,
                    'type' => gettype($guestBoardType)
                ]);
                // Fetch all board_type_rules for this board_type
                $allBoardRules = \DB::table('board_type_rules')
                    ->where('board_type_rules_id', $guestBoardType)
                    ->get();
                \Log::info('AllBoardTypeRules', [
                    'guestBoardType' => $guestBoardType,
                    'allBoardRules' => $allBoardRules
                ]);
                // Check board_type_rules for this hotel
                $boardRule = \DB::table('board_type_rules')
                    ->where('company_id', $companyId)
                    ->where('hotel_id', $restaurant->hotel_id)
                    ->where('board_type_rules_id', $guestBoardType)
                    ->first();
                $freeCount = $boardRule ? $boardRule->free_count : 0;
                $hotelFreeCount = $hotel->free_count ?? 0;
                $maxFree = $freeCount + $hotelFreeCount;
                $guestReservationCount = \DB::table('reservations')
                    ->where('guest_reservations_id', $guestReservationId)
                    ->count();
                \Log::info('BoardTypeCheck', [
                    'restaurant_id' => $restaurant->restaurants_id,
                    'restaurant_name' => $restaurant->name,
                    'isSameHotel' => $isSameHotel,
                    'guestBoardType' => $guestBoardType,
                    'boardRule' => $boardRule,
                    'freeCount' => $freeCount,
                    'hotelFreeCount' => $hotelFreeCount,
                    'maxFree' => $maxFree,
                    'guestReservationCount' => $guestReservationCount
                ]);
                if ($guestReservationCount < $maxFree) {
                    $status['free_with_board_type'] = true;
                    $status['reason'] = 'Free with your board type';
                } else {
                    $status['paid'] = true;
                    $status['reason'] = 'Exceeded board type free reservations';
                }
            } else {
                // Fallback to old logic
                if ($restaurant->always_paid_free === 0) {
                    $status['free'] = true;
                    $status['reason'] = 'Always free';
                } elseif ($restaurant->always_paid_free === 1) {
                    $status['paid'] = true;
                    $status['reason'] = 'Always paid';
                } else {
                    // Use hotel free_count logic
                    $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
                    \Log::info('HotelFreeCountCheck', [
                        'restaurant_id' => $restaurant->restaurants_id,
                        'restaurant_name' => $restaurant->name,
                        'remainingFreeReservations' => $remainingFreeReservations
                    ]);
                    if ($remainingFreeReservations > 0) {
                        $status['free'] = true;
                        $status['reason'] = 'Free reservations remaining';
                    } else {
                        $status['paid'] = true;
                        $status['reason'] = 'No free reservations remaining';
                    }
                }
            }
            $restaurantStatuses[$restaurant->restaurants_id] = $status;
        }
        
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
        
        // Calculate correct remaining free reservations for the guest
        $remainingFreeReservations = null;
        if ($guestBoardType && $guestHotelId == $hotel->hotel_id) {
            $boardRule = \DB::table('board_type_rules')
                ->where('company_id', $companyId)
                ->where('board_type_rules_id', $guestBoardType)
                ->first();
            \Log::info('BoardTypeRuleQuery', [
                'query_conditions' => [
                    'company_id' => $companyId,
                    'board_type_rules_id' => $guestBoardType
                ],
                'boardRule' => $boardRule,
                'sql' => \DB::table('board_type_rules')
                    ->where('company_id', $companyId)
                    ->where('board_type_rules_id', $guestBoardType)
                    ->toSql()
            ]);
            $freeCount = $boardRule ? $boardRule->free_count : 0;
            $hotelFreeCount = $hotel->free_count ?? 0;
            $maxFree = $freeCount + $hotelFreeCount;
            $guestReservationCount = \DB::table('reservations')
                ->where('guest_reservations_id', $guestReservationId)
                ->count();
            $remainingFreeReservations = max($maxFree - $guestReservationCount, 0);
        } else {
            $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
        }
        
        return View::make('restaurants.index', compact('restaurants', 'mealTypes', 'remainingFreeReservations', 'hotel', 'roomNumber', 'restaurantStatuses'));
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
        
        $hotel = Hotel::find(Session::get('hotel_id'));
        $roomNumber = Session::get('room_number');
        
        // Get restaurants that serve the selected meal type
        $restaurants = $hotel->getAvailableRestaurants()
            ->filter(function($restaurant) use ($request) {
                return $restaurant->mealTypes()
                    ->where('meal_types_id', $request->meal_type_id)
                    ->exists();
            });
            
        // Get remaining free reservations
        $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
        
        return View::make('restaurants.index', compact('restaurants', 'remainingFreeReservations'));
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
        $hotel = Hotel::find(Session::get('hotel_id'));
        
        // Get restaurant translation
        $translation = $restaurant->getTranslation('en');
        
        // Get available pricing times for the restaurant
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
        
        // Get all menus for the company
        $menus = Menu::where('company_id', $restaurant->company_id)->get();
        
        // Fetch menu categories based on menus_id
        $menuCategories = MenuCategory::with(['translations' => function($query) {
                $query->where('language_code', 'en');
            }])
            ->whereIn('menus_id', $menus->pluck('menus_id'))
            ->get();
        
        // Add logging for debugging
        \Log::info('Menu categories:', ['categories' => $menuCategories]);
        \Log::info('Menus:', ['menus' => $menus]);
        \Log::info('Translation:', ['translation' => $translation]);
        
        return View::make('restaurants.menu', compact('restaurant', 'translation', 'menuCategories', 'menus', 'pricingTimes'));
    }
    
    /**
     * Show the form for making a reservation at a restaurant.
     *
     * @param  int  $restaurantId
     * @return \Illuminate\Http\Response
     */
    public function showReservationForm($restaurantId)
    {
        \Log::info('showReservationForm called');
        $restaurant = Restaurant::findOrFail($restaurantId);
        $hotel = Hotel::find(Session::get('hotel_id'));
        $roomNumber = Session::get('room_number');
        $guestReservationId = Session::get('guest_reservation_id');
        $guestReservation = \App\Models\GuestReservation::find($guestReservationId);
        $guestBoardType = $guestReservation ? $guestReservation->board_type : null;
        $guestHotelId = $guestReservation ? $guestReservation->hotel_id : null;
        $companyId = $hotel->company_id;
        // Add debug logging for board type logic
        \Log::info('BoardTypeLogicDebug', [
            'guestBoardType' => $guestBoardType,
            'guestHotelId' => $guestHotelId,
            'restaurantHotelId' => $restaurant->hotel_id,
            'guestReservationId' => $guestReservationId
        ]);
        
        // Determine free/paid status for this reservation
        $freePaidStatus = [
            'free_with_board_type' => false,
            'free' => false,
            'paid' => false,
            'reason' => ''
        ];
        $guestReservationCount = \DB::table('reservations')
            ->where('guest_reservations_id', $guestReservationId)
            ->count();
        if ($guestBoardType && $guestHotelId == $restaurant->hotel_id) {
            $boardRule = \DB::table('board_type_rules')
                ->where('company_id', $companyId)
                ->where('board_type_rules_id', $guestBoardType)
                ->first();
            \Log::info('BoardTypeRuleQuery', [
                'query_conditions' => [
                    'company_id' => $companyId,
                    'board_type_rules_id' => $guestBoardType
                ],
                'boardRule' => $boardRule,
                'sql' => \DB::table('board_type_rules')
                    ->where('company_id', $companyId)
                    ->where('board_type_rules_id', $guestBoardType)
                    ->toSql()
            ]);
            $freeCount = $boardRule ? $boardRule->free_count : 0;
            $hotelFreeCount = $hotel->free_count ?? 0;
            $maxFree = $freeCount + $hotelFreeCount;
            if ($guestReservationCount < $maxFree) {
                $freePaidStatus['free_with_board_type'] = true;
                $freePaidStatus['reason'] = 'Free with your board type';
            } else {
                $freePaidStatus['paid'] = true;
                $freePaidStatus['reason'] = 'Exceeded board type free reservations';
            }
        } else {
            if ($restaurant->always_paid_free === 0) {
                $freePaidStatus['free'] = true;
                $freePaidStatus['reason'] = 'Always free';
            } elseif ($restaurant->always_paid_free === 1) {
                $freePaidStatus['paid'] = true;
                $freePaidStatus['reason'] = 'Always paid';
            } else {
                $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
                if ($remainingFreeReservations > 0) {
                    $freePaidStatus['free'] = true;
                    $freePaidStatus['reason'] = 'Free reservations remaining';
                } else {
                    $freePaidStatus['paid'] = true;
                    $freePaidStatus['reason'] = 'No free reservations remaining';
                }
            }
        }
        
        // Calculate remaining free reservations for compatibility with the view
        $remainingFreeReservations = null;
        if ($guestBoardType && $guestHotelId == $restaurant->hotel_id) {
            $boardRule = \DB::table('board_type_rules')
                ->where('company_id', $companyId)
                ->where('board_type_rules_id', $guestBoardType)
                ->first();
            $freeCount = $boardRule ? $boardRule->free_count : 0;
            $hotelFreeCount = $hotel->free_count ?? 0;
            $maxFree = $freeCount + $hotelFreeCount;
            $guestReservationCount = \DB::table('reservations')
                ->where('guest_reservations_id', $guestReservationId)
                ->count();
            $remainingFreeReservations = max($maxFree - $guestReservationCount, 0);
        } else {
            $remainingFreeReservations = $hotel->getRemainingFreeReservations($roomNumber);
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
        
        // Get guests from the room
        $guests = DB::table('guest_details')
            ->join('guest_reservations', 'guest_details.guest_reservations_id', '=', 'guest_reservations.guest_reservations_id')
            ->where('guest_reservations.guest_reservations_id', $guestReservationId)
            ->where('guest_reservations.room_number', $roomNumber)
            ->get();
        
        return View::make('restaurants.reserve', compact('restaurant', 'pricingTimes', 'remainingFreeReservations', 'guests', 'hotel', 'freePaidStatus'));
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
            ->where('hotel_id', Session::get('hotel_id'))
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

        return Response::json([
            'success' => true,
            'times' => $times
        ]);
    }
}
