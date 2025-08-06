<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlLeave extends Model
{
    use HasFactory;
    
    protected $table = 'fl_leave';

    protected $fillable = [
        'em_id',
        'name',
        'start_date',
        'end_date',
        'reason',
    ];

    public function driver()
    {
        return $this->belongsTo(FlDriver::class, 'em_id', 'em_id');
    }
}
