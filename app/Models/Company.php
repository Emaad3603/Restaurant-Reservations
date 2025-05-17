<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'company_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo_url',
        'active'
    ];
    
    /**
     * Get the hotels for the company.
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the restaurants for the company.
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the meal types for the company.
     */
    public function mealTypes()
    {
        return $this->hasMany(MealType::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the guest reservations for the company.
     */
    public function guestReservations()
    {
        return $this->hasMany(GuestReservation::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the reservations for the company.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'company_id', 'company_id');
    }
}
