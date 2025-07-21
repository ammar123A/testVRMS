<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlVehicle extends Model
{
    protected $table = 'fl_vehicle';
    protected $primaryKey = 'vehicle_id';
    public $timestamps = false;
    protected $fillable = [
        'plate_number', 'model', 'type', 'status', 'registration_date', 'no_vehicle', 'vehicle_request', 'department', 'em_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo(FlRequest::class, 'vehicle_id');
    }
}
