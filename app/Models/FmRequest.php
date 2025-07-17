<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FmRequest extends Model
{
    protected $table = 'fm_request';
    protected $primaryKey = 'maintenance_request_id';
    protected $fillable = [
        'vehicle_id', 'driver_id', 'complaint', 'complaint_date'
    ];
}
