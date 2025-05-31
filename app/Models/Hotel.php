<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $hotel_id
 * @property string $name
 * @property int $verification_type
 * @property int $company_id
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property int|null $free_count
 * @property string|null $time_zone
 * @property int|null $plus_days_adjust
 * @property int|null $minus_days_adjust
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $active
 * @property int $restricted_restaurants
 * @property string|null $logo_url
 * @property-read \App\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GuestReservation> $guestReservations
 * @property-read int|null $guest_reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurant> $restaurants
 * @property-read int|null $restaurants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereFreeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereMinusDaysAdjust($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel wherePlusDaysAdjust($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereRestrictedRestaurants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereVerificationType($value)
 * @mixin \Eloquent
 */
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
     * Get the reservation status for a specific restaurant
     * Returns an array with:
     * - is_free: boolean indicating if the reservation is free
     * - message: string explaining the status
     * - type: string indicating the type of status (success, warning, info)
     */
    public function getRestaurantReservationStatus($restaurantId, $roomNumber)
    {
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return [
                'is_free' => false,
                'message' => 'Restaurant not found',
                'type' => 'warning'
            ];
        }

        // Check restaurant's always_paid_free setting first
        if ($restaurant->always_paid_free === 0) {
            return [
                'is_free' => true,
                'message' => 'This restaurant is always free',
                'type' => 'success'
            ];
        }

        if ($restaurant->always_paid_free === 1) {
            return [
                'is_free' => false,
                'message' => 'This restaurant is always paid',
                'type' => 'warning'
            ];
        }

        // If always_paid_free is null, check hotel's free count system
        if ($this->isAlwaysFree()) {
            return [
                'is_free' => true,
                'message' => 'All reservations at this hotel are free',
                'type' => 'success'
            ];
        }

        if ($this->isAlwaysPaid()) {
            return [
                'is_free' => false,
                'message' => 'All reservations at this hotel require payment',
                'type' => 'warning'
            ];
        }

        $remainingFree = $this->getRemainingFreeReservations($roomNumber);
        if ($remainingFree <= 0) {
            return [
                'is_free' => false,
                'message' => "You have used all {$this->free_count} free reservations. Additional reservations will require payment.",
                'type' => 'warning'
            ];
        }

        return [
            'is_free' => true,
            'message' => "You have {$remainingFree} of {$this->free_count} free reservations remaining",
            'type' => 'info'
        ];
    }
    
    /**
     * Check if a restaurant is restricted for this hotel
     */
    public function isRestaurantRestricted($restaurantId)
    {
        if ($this->restricted_restaurants === 0) {
            return false; // No restrictions
        }
        
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return true; // Restaurant not found, consider it restricted
        }
        
        if ($this->restricted_restaurants === 1) {
            // Only allow restaurants from this hotel
            return $restaurant->hotel_id !== $this->hotel_id;
        }
        
        if ($this->restricted_restaurants === 2) {
            // Allow all restaurants but mark as paid for cross-hotel
            return $restaurant->hotel_id !== $this->hotel_id;
        }
        
        return false;
    }
    
    /**
     * Get the restriction type for a restaurant
     * Returns:
     * 0 - No restrictions
     * 1 - Restricted to hotel's restaurants only
     * 2 - Cross-hotel allowed but paid
     */
    public function getRestaurantRestrictionType($restaurantId)
    {
        if ($this->restricted_restaurants === 0) {
            return 0; // No restrictions
        }
        
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return 1; // Restaurant not found, consider it restricted
        }
        
        if ($restaurant->hotel_id === $this->hotel_id) {
            return 0; // Same hotel, no restrictions
        }
        
        return $this->restricted_restaurants; // Return the restriction type (1 or 2)
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
