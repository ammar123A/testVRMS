<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlComplaint extends Model
{
    use HasFactory;
    
    protected $table = 'complaints';
    protected $fillable = 
    [
        'vehicle_id',
        'plate_number',
        'type',
        'model',
        'chassis_no',
        'engine_no',
        'colour',
        'odometer',
        'fuel',
        'road_tax_expiry',
        'puspakom_expiry',
        'permit_expiry',
        'complaint',
        'em_id'
        
    ];

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
