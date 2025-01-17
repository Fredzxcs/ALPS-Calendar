<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Training;


class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $table = 'company';

    // Define the columns that are mass assignable
    protected $fillable = [
        'company_name',
        'contact_person',
        'contact_number',
        'email',
    ];

    // Disable timestamps if not used in the database
    public $timestamps = false;

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

}
