<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlPermission extends Model
{
    protected $table = 'fl_permission';
    protected $fillable = ['em_id', 'name', 'email'];
}
