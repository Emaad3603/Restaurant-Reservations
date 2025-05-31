<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $meal_types_id
 * @property int|null $company_id
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int|null $active
 * @property string|null $label
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurant> $restaurants
 * @property-read int|null $restaurants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MealTypeTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereMealTypesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealType whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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
    protected $primaryKey = 'meal_types_id';
    
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
        return $this->hasManyThrough(
            Restaurant::class,
            Reservation::class,
            'meal_types_id', // Foreign key on reservations table
            'restaurants_id', // Foreign key on restaurants table
            'meal_types_id', // Local key on meal_types table
            'restaurant_id' // Local key on reservations table
        );
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
        return $this->hasMany(Reservation::class, 'meal_types_id', 'meal_types_id');
    }
}
