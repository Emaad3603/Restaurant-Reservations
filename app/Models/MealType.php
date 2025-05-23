<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealType extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meal_types';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'meal_type_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id',
        'order',
        'language_code'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the company associated with the meal type.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the restaurants that offer this meal type.
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_meal_types', 'meal_type_id', 'restaurant_id')
                    ->withPivot('id');
    }
    
    /**
     * Get the translations for the meal type.
     */
    public function translations()
    {
        return $this->hasMany(MealTypeTranslation::class, 'meal_types_id', 'meal_types_id');
    }
    
    /**
     * Get the meal type's translation for the specified language.
     */
    public function getTranslation($languageCode = 'en')
    {
        return $this->translations()->where('language_code', $languageCode)->first();
    }
    
    /**
     * Get the reservations for the meal type.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'meal_type_id', 'meal_type_id');
    }
}
