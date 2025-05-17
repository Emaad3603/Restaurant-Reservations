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
     * Get the reservations for the meal type.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'meal_type_id', 'meal_type_id');
    }
}
