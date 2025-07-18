<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlPart extends Model
{
    protected $table = 'fl_parts';
    protected $fillable = ['category', 'name'];
}
