<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get the guest details for the guest reservation.
     */
    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'guest_reservations_id', 'guest_reservations_id');
    }
    
    /**
     * Get the reservations for the guest reservation.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }
}
