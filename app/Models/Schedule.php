<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Training;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [

        'training_id',
        'from_date',
        'to_date',
        'from_time',
        'to_time',
        'google_event_id'

    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
