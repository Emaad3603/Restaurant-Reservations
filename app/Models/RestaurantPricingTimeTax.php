<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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