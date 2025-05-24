<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxTranslation extends Model
{
    protected $table = 'taxes_translation';
    
    protected $fillable = [
        'taxes_id',
        'name',
        'language_code'
    ];
    
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'taxes_id', 'taxes_id');
    }
} 