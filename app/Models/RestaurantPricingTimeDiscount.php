<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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