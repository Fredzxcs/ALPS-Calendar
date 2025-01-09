<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;
use App\Models\User;


class Training extends Model
{
    protected $table = 'training';

    protected $fillable = [
        'course',
        'facilitator_id',
        'company',
        'assistant_id',
        'credentials_email',
        'credentials_password',
        'mode',
        'location',
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'training_id');
    }

    public function facilitator()
    {
        return $this->hasOne(User::class, 'id');
    }
}
