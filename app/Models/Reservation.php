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
        'guest_reservations_id',
        'room_number',
        'pax',
        'names',
        'restaurant_id',
        'day',
        'time',
        'company_id',
        'guest_hotel_id',
        'restaurant_hotel_id',
        'canceled',
        'ended',
        'created_at',
        'qrcode',
        'created_by',
        'canceled_by',
        'canceled_at',
        'currencies_id',
        'price',
        'exchange_rate',
        'paid',
        'always_paid_free',
        'taxes',
        'discounts',
        'original_price',
        'sub_total',
        'after_tax',
        'total_ammount_due',
        'per_person',
        'reservation_by_room',
        'time_zone',
        'meal_types_id',
        'menus_id'
    ];
    
    /**
     * Get the restaurant associated with the reservation.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }
    
    /**
     * Get the guest reservation associated with the reservation.
     */
    public function guestReservation()
    {
        return $this->belongsTo(GuestReservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id', 'currencies_id');
    }

    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_types_id', 'meal_types_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menus_id', 'menus_id');
    }

    const UPDATED_AT = null;
}
