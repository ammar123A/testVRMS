<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlWr extends Model
{
    protected $table = 'fl_wr';
    protected $primaryKey = 'wr_id';
    protected $fillable = [
        'request_id', 'approved_by', 'status'
    ];
}
