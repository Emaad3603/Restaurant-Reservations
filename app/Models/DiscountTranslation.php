<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountTranslation extends Model
{
    protected $table = 'discounts_translation';
    
    protected $fillable = [
        'discounts_id',
        'name',
        'language_code'
    ];
    
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discounts_id', 'discounts_id');
    }
} 