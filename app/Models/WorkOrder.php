<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $table = 'fl_wo'; // assuming legacy table name

    protected $primaryKey = 'wo_id';
    public $incrementing = false; // if `wo_id` is not auto-increment
    public $timestamps = false;   // disable timestamps if not used

    protected $fillable = [
        'wo_id', 'wr_id', 'datetime_assigned', 'date_send', 'charted', 'status', 'company_id'
        // add all actual columns as needed
    ];

    // Relationships
    public function drivers()
    {
        return $this->hasMany(FlDriver::class, 'wo_id', 'wo_id');
    }

    // public function workRequest()
    // {
    //     return $this->belongsTo(WorkRequest::class, 'wr_id', 'wr_id');
    // }

    // public function company()
    // {
    //     return $this->belongsTo(Company::class, 'company_id', 'com_id');
    // }
}

