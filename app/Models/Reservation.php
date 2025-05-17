<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'reservations_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_time',
        'guest_reservations_id',
        'restaurant_id',
        'status',
        'pax',
        'meal_type_id',
        'customer_request',
        'restaurant_remarks',
        'company_id',
        'token',
        'confirmation_code'
    ];
    
    /**
     * Get the guest reservation associated with the reservation.
     */
    public function guestReservation()
    {
        return $this->belongsTo(GuestReservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }
    
    /**
     * Get the restaurant associated with the reservation.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }
    
    /**
     * Get the meal type associated with the reservation.
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_type_id', 'meal_type_id');
    }
    
    /**
     * Get the company associated with the reservation.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
