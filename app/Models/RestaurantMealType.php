<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \App\Models\MealType|null $mealType
 * @property-read \App\Models\Restaurant|null $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantMealType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantMealType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestaurantMealType query()
 * @mixin \Eloquent
 */
class RestaurantMealType extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurant_meal_types';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'restaurant_id',
        'meal_type_id'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the restaurant that has this meal type.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }
    
    /**
     * Get the meal type associated with the restaurant.
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_type_id', 'meal_type_id');
    }
} 