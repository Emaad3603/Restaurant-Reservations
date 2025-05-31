<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $reservations_id
 * @property int|null $guest_reservations_id
 * @property string|null $room_number
 * @property int|null $pax
 * @property string|null $names
 * @property int|null $restaurant_id
 * @property string|null $day
 * @property string|null $time
 * @property int|null $company_id
 * @property int|null $guest_hotel_id
 * @property int|null $restaurant_hotel_id
 * @property int|null $canceled
 * @property int|null $ended
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $qrcode
 * @property string|null $created_by
 * @property string|null $canceled_by
 * @property string|null $canceled_at
 * @property int|null $currencies_id
 * @property string|null $price
 * @property string|null $exchange_rate
 * @property int|null $paid
 * @property int|null $always_paid_free
 * @property string|null $taxes
 * @property string|null $discounts
 * @property string|null $original_price
 * @property string|null $sub_total
 * @property string|null $after_tax
 * @property string|null $total_ammount_due
 * @property int|null $per_person
 * @property int|null $reservation_by_room
 * @property string|null $time_zone
 * @property int|null $meal_types_id
 * @property int|null $menus_id
 * @property-read \App\Models\Currency|null $currency
 * @property-read \App\Models\GuestReservation|null $guestReservation
 * @property-read \App\Models\MealType|null $mealType
 * @property-read \App\Models\Menu|null $menu
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereAfterTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereAlwaysPaidFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCanceled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCanceledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCurrenciesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereEnded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestReservationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereMealTypesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereMenusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePerPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereQrcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereReservationByRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereReservationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereRestaurantHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereRoomNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereTotalAmmountDue($value)
 * @mixin \Eloquent
 */
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
        return $this->belongsTo(\App\Models\MealType::class, 'meal_types_id', 'meal_types_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menus_id', 'menus_id');
    }

    const UPDATED_AT = null;
}
