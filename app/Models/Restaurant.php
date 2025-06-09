<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $restaurants_id
 * @property int|null $company_id
 * @property string|null $name
 * @property int|null $capacity
 * @property string|null $created_at
 * @property int $active
 * @property int|null $hotel_id
 * @property string|null $logo_url
 * @property int|null $always_paid_free
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Hotel|null $hotel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MealType> $mealTypes
 * @property-read int|null $meal_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu> $menus
 * @property-read int|null $menus_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestaurantTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereAlwaysPaidFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereRestaurantsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurant whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Restaurant extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurants';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'restaurants_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'capacity',
        'created_at',
        'active',
        'hotel_id',
        'logo_url',
        'always_paid_free',
        'created_by',
        'updated_at',
        'updated_by'
    ];
    
    /**
     * Get the hotel that owns the restaurant.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
    
    /**
     * Get the company that owns the restaurant.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the translations for the restaurant.
     */
    public function translations()
    {
        return $this->hasMany(RestaurantTranslation::class, 'restaurants_id', 'restaurants_id');
    }
    
    /**
     * Get the reservations for the restaurant.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'restaurant_id', 'restaurants_id');
    }
    
    /**
     * Get the meal types for the restaurant.
     */
    public function mealTypes()
    {
        return $this->hasManyThrough(
            MealType::class,
            Reservation::class,
            'restaurant_id', // Foreign key on reservations table
            'meal_types_id', // Foreign key on meal_types table
            'restaurants_id', // Local key on restaurants table
            'meal_types_id' // Local key on reservations table
        );
    }
    
    /**
     * Get the restaurant's translation for the specified language.
     */
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurants_id', 'restaurants_id');
    }

    public $timestamps = false;

    /**
     * Get the logo URL attribute.
     *
     * @param string|null $value
     * @return string|null
     */
    public function getLogoUrlAttribute($value)
    {
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) return $value;
        if (str_starts_with($value, '/')) return $value;
        
        return url('/../shared_storage/' . $value);
    }
}
