<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hotels';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'hotel_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'verification_type',
        'company_id',
        'created_by',
        'updated_at',
        'updated_by',
        'free_count',
        'time_zone',
        'plus_days_adjust',
        'minus_days_adjust',
        'created_at',
        'active',
        'restricted_restaurants',
        'logo_url'
    ];
    
    /**
     * Get the company that owns the hotel.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the restaurants associated with the hotel.
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'hotel_id', 'hotel_id');
    }
    
    /**
     * Get the guest reservations associated with the hotel.
     */
    public function guestReservations()
    {
        return $this->hasMany(GuestReservation::class, 'hotel_id', 'hotel_id');
    }
}
