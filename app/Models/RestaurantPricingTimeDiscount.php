<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $restaurant_pricing_times_discounts_id
 * @property int|null $restaurant_pricing_times_id
 * @property int|null $discounts_id
 * @property-read \App\Models\Discount|null $discount
 * @property-read \App\Models\RestaurantPricingTime|null $pricingTime
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount whereDiscountsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount whereRestaurantPricingTimesDiscountsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeDiscount whereRestaurantPricingTimesId($value)
 * @mixin \Eloquent
 */
class RestaurantPricingTimeDiscount extends Model
{
    protected $table = 'restaurant_pricing_times_discounts';
    
    protected $fillable = [
        'restaurant_pricing_times_id',
        'discounts_id'
    ];
    
    public function pricingTime()
    {
        return $this->belongsTo(RestaurantPricingTime::class, 'restaurant_pricing_times_id', 'id');
    }
    
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discounts_id', 'discounts_id');
    }
} 