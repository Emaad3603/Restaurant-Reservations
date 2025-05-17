<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'birty_date',
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
