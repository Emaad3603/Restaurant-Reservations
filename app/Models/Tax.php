<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $primaryKey = 'taxes_id';
    
    protected $fillable = [
        'name',
        'percentage',
        'active'
    ];
    
    public function translations()
    {
        return $this->hasMany(TaxTranslation::class, 'taxes_id', 'taxes_id');
    }
    
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
} 