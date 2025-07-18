<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlCompany extends Model
{
    protected $table = 'fl_commpany';

    protected $primaryKey = 'comp_id';

    public $incrementing = true;
    protected $fillable = [
        'name', 'roc', 'contact_person', 'tel', 'mobile', 'fax', 'address', 'state'
    ];
}
