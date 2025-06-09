<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $company_id
 * @property string|null $company_name
 * @property int $currency_id
 * @property string $company_uuid
 * @property string|null $logo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GuestReservation> $guestReservations
 * @property-read int|null $guest_reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hotel> $hotels
 * @property-read int|null $hotels_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MealType> $mealTypes
 * @property-read int|null $meal_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurant> $restaurants
 * @property-read int|null $restaurants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLogoUrl($value)
 * @mixin \Eloquent
 */
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
        'company_name',
        'currency_id',
        'company_uuid',
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

    /**
     * Get the logo URL attribute.
     *
     * @param string|null $value
     * @return string|null
     */
    public function getLogoUrlAttribute($value)
    {
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) return $value;
        if (str_starts_with($value, '/')) return $value;
        
        return url('/../shared_storage/' . $value);
    }
}
