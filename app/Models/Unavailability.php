<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unavailability extends Model
{
    protected $table = 'unavailability';

    protected $fillable = [

        'reason',
        'user_id',
        'from_date',
        'to_date',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
