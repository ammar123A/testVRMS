<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlVehicle extends Model
{
    use HasFactory;
    
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
