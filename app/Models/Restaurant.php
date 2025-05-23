<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(MealType::class, 'restaurant_meal_types', 'restaurant_id', 'meal_type_id')
                    ->withPivot('id');
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
}
