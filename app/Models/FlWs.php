<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlWs extends Model
{
    protected $table = 'workshops';
    protected $primaryKey = 'ws_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'contact_person',
        'tel',
        'fax',
    ];
}
