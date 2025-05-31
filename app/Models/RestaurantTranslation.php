<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $restaurant_translations_id
 * @property int|null $restaurants_id
 * @property string|null $language_code
 * @property string|null $cuisine
 * @property string|null $about
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation whereCuisine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation whereRestaurantTranslationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantTranslation whereRestaurantsId($value)
 * @mixin \Eloquent
 */
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
