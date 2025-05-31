<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereGuestDetailsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereGuestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereGuestReservationsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereGuestType($value)
 * @mixin \Eloquent
 */
class Guest extends Model
{
    protected $table = 'guest_details';
    protected $primaryKey = 'guest_details_id';
    public $timestamps = false;

    protected $fillable = [
        'guest_reservations_id',
        'guest_name',
        'guest_type',
        'birth_date'
    ];

    public function guestReservation()
    {
        return $this->belongsTo(GuestReservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }
} 