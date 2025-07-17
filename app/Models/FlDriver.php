<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlDriver extends Model
{
    protected $table = 'fl_driver';
    protected $primaryKey = 'driver_id';
    protected $fillable = [
        'name', 'ic_number', 'license_number', 'phone', 'em_id'
    ];

        public function userByEmId()
    {
        return $this->belongsTo(User::class, 'em_id', 'em_id');
    }
}
