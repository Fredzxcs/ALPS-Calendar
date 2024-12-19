<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    private $table = 'training';

    protected $fillable = [
        'course_id',
        'trainor_id',
        'company_id',
        'assistant_id',
    ];
}
