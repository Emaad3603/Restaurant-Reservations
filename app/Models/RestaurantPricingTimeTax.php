<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $restaurant_pricing_times_taxes_id
 * @property int|null $restaurant_pricing_times_id
 * @property int|null $taxes_id
 * @property-read \App\Models\RestaurantPricingTime|null $pricingTime
 * @property-read \App\Models\Tax|null $tax
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax whereRestaurantPricingTimesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax whereRestaurantPricingTimesTaxesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantPricingTimeTax whereTaxesId($value)
 * @mixin \Eloquent
 */
class RestaurantPricingTimeTax extends Model
{
    protected $table = 'restaurant_pricing_times_taxes';
    
    protected $fillable = [
        'restaurant_pricing_times_id',
        'taxes_id'
    ];
    
    public function pricingTime()
    {
        return $this->belongsTo(RestaurantPricingTime::class, 'restaurant_pricing_times_id', 'id');
    }
    
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'taxes_id', 'taxes_id');
    }
} 