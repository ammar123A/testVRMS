<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DV extends Model
{
    protected $table = 'dv';
    protected $primaryKey = 'dv_id';
    public $incrementing = false; // since it's a string
    protected $keyType = 'string';

    protected $fillable = ['dv_id', 'name', 'm_st'];
}
