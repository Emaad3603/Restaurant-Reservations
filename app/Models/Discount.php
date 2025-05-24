<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discounts_id';
    
    protected $fillable = [
        'name',
        'percentage',
        'active'
    ];
    
    public function translations()
    {
        return $this->hasMany(DiscountTranslation::class, 'discounts_id', 'discounts_id');
    }
    
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
} 