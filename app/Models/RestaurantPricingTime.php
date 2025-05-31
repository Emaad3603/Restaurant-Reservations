<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $restaurant_pricing_times_id
 * @property int|null $company_id
 * @property int|null $restaurant_id
 * @property int|null $currency_id
 * @property string|null $year
 * @property string|null $month
 * @property string|null $day
 * @property string|null $time
 * @property int|null $per_person
 * @property string|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $meal_type
 * @property int $reservation_by_room
 * @property int $extra_seats
 * @property string|null $menu_url
 * @property int|null $hotel_id
 * @property int|null $menus_id
 * @property int|null $calculate_price
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestaurantPricingTimeDiscount> $discounts
 * @property-read int|null $discounts_count
 * @property-read \App\Models\Restaurant|null $restaurant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestaurantPricingTimeTax> $taxes
 * @property-read int|null $taxes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereCalculatePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereExtraSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereMealType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereMenuUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereMenusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime wherePerPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereReservationByRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereRestaurantPricingTimesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTime whereYear($value)
 * @mixin \Eloquent
 */
class RestaurantPricingTime extends Model
{
    protected $table = 'restaurant_pricing_times';
    
    protected $fillable = [
        'restaurant_id',
        'date',
        'start_time',
        'end_time',
        'price',
        'active'
    ];
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }
    
    public function discounts()
    {
        return $this->hasMany(RestaurantPricingTimeDiscount::class, 'restaurant_pricing_times_id', 'id');
    }
    
    public function taxes()
    {
        return $this->hasMany(RestaurantPricingTimeTax::class, 'restaurant_pricing_times_id', 'id');
    }
} 