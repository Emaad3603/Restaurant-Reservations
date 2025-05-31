<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $meal_types_translation_id
 * @property int|null $meal_types_id
 * @property string|null $name
 * @property string $language_code
 * @property-read \App\Models\MealType|null $mealType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation whereMealTypesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation whereMealTypesTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MealTypeTranslation whereName($value)
 * @mixin \Eloquent
 */
class MealTypeTranslation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meal_types_translation';
    
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
        'meal_types_id',
        'language_code',
        'name',
        'description'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the meal type that owns the translation.
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_types_id', 'meal_types_id');
    }
} 