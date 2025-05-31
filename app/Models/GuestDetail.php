<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $guest_details_id
 * @property int|null $guest_reservations_id
 * @property string|null $guest_name
 * @property string|null $guest_type
 * @property string|null $birth_date
 * @property-read \App\Models\GuestReservation|null $guestReservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail whereGuestDetailsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail whereGuestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail whereGuestReservationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuestDetail whereGuestType($value)
 * @mixin \Eloquent
 */
class GuestDetail extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guest_details';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'guest_details_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guest_reservations_id',
        'first_name',
        'last_name',
        'birth_date',
        'room_pax',
        'guest_type',
        'main_guest'
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get the guest reservation that the guest detail belongs to.
     */
    public function guestReservation()
    {
        return $this->belongsTo(GuestReservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }
}
