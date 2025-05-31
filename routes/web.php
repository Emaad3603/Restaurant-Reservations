<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome page
Route::get('/', function () {
    // Clear any existing session data when starting a new reservation
    session()->forget(['hotel_id', 'hotel_name', 'room_number', 'guest_reservation_id', 
                     'guest_name', 'number_of_guests', 'reservation_id']);
    return view('custom_welcome');
})->name('welcome');

// Hotel routes
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::post('/hotels/{hotelId}/validate-room', [HotelController::class, 'validateRoom'])->name('hotels.validateRoom');
Route::get('/hotels/{hotelId}/reservation-status', [HotelController::class, 'getReservationStatus'])->name('hotels.reservationStatus');

// Guest routes
Route::get('/guest/create', [GuestController::class, 'create'])->name('guest.create');
Route::post('/guest', [GuestController::class, 'store'])->name('guest.store');

// Restaurant routes
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/filter', [RestaurantController::class, 'filter'])->name('restaurants.filter');
Route::get('/restaurants/{restaurantId}/menu', [RestaurantController::class, 'showMenu'])->name('restaurants.menu');
Route::get('/restaurants/{restaurantId}/reserve', [RestaurantController::class, 'showReservationForm'])->name('restaurants.reserve');
Route::post('/restaurants/available-times', [RestaurantController::class, 'getAvailableTimes'])->name('restaurants.available-times');

// Pricing routes
Route::get('/restaurants/{restaurantId}/time-slots', [PricingController::class, 'getAvailableTimeSlots'])->name('pricing.timeSlots');
Route::post('/pricing/calculate', [PricingController::class, 'calculatePrice'])->name('pricing.calculate');

// Reservation routes
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
Route::get('/reservations/{id}/print', [ReservationController::class, 'printReceipt'])->name('reservations.print');

// Meal type ID route
Route::get('/meal-type-id', function (\Illuminate\Http\Request $request) {
    $label = $request->query('label');
    $mealType = \App\Models\MealType::where('label', $label)->first();
    return response()->json(['meal_types_id' => $mealType ? $mealType->meal_types_id : null]);
});
