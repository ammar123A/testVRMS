<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlVehicle extends Model
{
    protected $table = 'fl_vehicle';
    protected $primaryKey = 'vehicle_id';
    protected $fillable = [
        'plate_number', 'model', 'type', 'status', 'registration_date', 'no_vehicle', 'vehicle_request', 'department'
    ];

    public function vehicle()
    {
        return $this->belongsTo(FlRequest::class, 'vehicle_id');
    }
}
