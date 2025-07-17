<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlRequest extends Model
{
    protected $table = 'fl_request';
    protected $primaryKey = 'request_id';
    protected $fillable = [
        'user_id',
        'purpose',
        'reservation_date',
        'status',
        'attention_to',
        'vote_ptj',
        'dept_faculty',
        'officer_email',
        'vehicle_request',
        'no_vehicle',
        'estimated_cost',
        'program',
        'booking_type',
        'pickup_point',
        'pickup_state',
        'destination',
        'destination_state',
        'remark',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'agree',
        'distance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function passengers()
    {
        return $this->hasMany(FlPassenger::class, 'request_id');
    }

    public function driver()
    {
        return $this->belongsTo(FlDriver::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(FlVehicle::class, 'vehicle_id');
    }
}
