<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Training;

class Account extends Model
{
    use HasFactory;

    protected $table = 'credentials';

    protected $fillable = [
        'account_email',
        'account_password',
    ];

    public $timestamps = false;

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
