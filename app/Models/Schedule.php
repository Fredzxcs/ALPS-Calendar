<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [

        'training_id',
        'from_date',
        'to_date',
        'from_time',
        'to_time'

    ];
}
