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
    
    /**
     * Check if the hotel is always free
     */
    public function isAlwaysFree()
    {
        return $this->free_count === -1;
    }
    
    /**
     * Check if the hotel is always paid
     */
    public function isAlwaysPaid()
    {
        return $this->free_count === 0;
    }
    
    /**
     * Get remaining free reservations for a room
     */
    public function getRemainingFreeReservations($roomNumber)
    {
        if ($this->isAlwaysFree()) {
            return -1; // Unlimited free reservations
        }
        
        if ($this->isAlwaysPaid()) {
            return 0; // No free reservations
        }
        
        $usedCount = $this->guestReservations()
            ->where('room_number', $roomNumber)
            ->where('status', 'checked_in')
            ->count();
            
        return max(0, $this->free_count - $usedCount);
    }
    
    /**
     * Check if a restaurant is restricted for this hotel
     */
    public function isRestaurantRestricted($restaurantId)
    {
        if (empty($this->restricted_restaurants)) {
            return false;
        }
        
        $restrictedRestaurants = explode(',', $this->restricted_restaurants);
        return in_array($restaurantId, $restrictedRestaurants);
    }
    
    /**
     * Get available restaurants for this hotel
     */
    public function getAvailableRestaurants()
    {
        return $this->restaurants()
            ->where('active', 1)
            ->whereNotIn('restaurants_id', function($query) {
                $query->select('restaurants_id')
                    ->from('restaurants')
                    ->whereRaw('FIND_IN_SET(restaurants_id, ?)', [$this->restricted_restaurants]);
            })
            ->get();
    }
}
