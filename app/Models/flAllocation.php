<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class flAllocation extends Model
{
    protected $table = 'fl_allocation';
    protected $primaryKey = 'altn_id';

    protected $fillable = [
        'altn_year',
        'altn_phb',
        'altn_category',
        'altn_allocation',
        'altn_date'
    ];
}
