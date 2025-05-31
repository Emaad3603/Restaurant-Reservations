<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $guest_reservations_id
 * @property string $reservation_id
 * @property string $room_number
 * @property string $arrival_date
 * @property string $departure_date
 * @property int|null $pax
 * @property string $status
 * @property int|null $hotel_id
 * @property int|null $company_id
 * @property string|null $board_type
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guest> $guestDetails
 * @property-read int|null $guest_details_count
 * @property-read \App\Models\Hotel|null $hotel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereBoardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereDepartureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereGuestReservationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation wherePax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereRoomNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestReservation whereStatus($value)
 * @mixin \Eloquent
 */
class GuestReservation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guest_reservations';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'guest_reservations_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reservation_id',
        'room_number',
        'arrival_date',
        'departure_date',
        'pax',
        'status',
        'hotel_id',
        'company_id',
        'board_type'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the hotel associated with the guest reservation.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
    
    /**
     * Get the company associated with the guest reservation.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    
    /**
     * Get the guest details associated with the guest reservation.
     */
    public function guestDetails()
    {
        return $this->hasMany(Guest::class, 'guest_reservations_id', 'guest_reservations_id');
    }
}
