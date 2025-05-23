<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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