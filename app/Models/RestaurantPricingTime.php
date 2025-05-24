<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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