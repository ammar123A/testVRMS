<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlCf extends Model
{
     use HasFactory;

    protected $table = 'fl_cf';

    protected $fillable = [
        'em_id',
        'em_number',
        'gred',
        'position',
        'dv_name',
        'site_name',
        'phone',
        'email',
        'user_name',
    ];
}
