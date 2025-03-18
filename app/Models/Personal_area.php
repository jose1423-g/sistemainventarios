<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal_area extends Model
{
    protected $table = 'personal_area';

    protected $fillable = [
        'fk_personal',
        'fk_area'
    ];
}
