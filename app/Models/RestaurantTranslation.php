<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTranslation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurants_translations';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'restaurant_translations_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'restaurants_id',
        'language_code',
        'cuisine',
        'about'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the restaurant that the translation belongs to.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurants_id', 'restaurants_id');
    }
}
