<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;
use App\Models\Course;
use App\Models\User;
use App\Models\Company;
use App\Models\Account;

class Training extends Model
{
    protected $table = 'training';

    protected $fillable = [
        'course_id',
        'facilitator_id',
        'company_id',
        'assistant',
        'platform',
        'mode',
        'location',
        'account_id',
        // 'credentials_id', --if credentials object is done
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'training_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function facilitator()
    {
        return $this->belongsTo(User::class, 'facilitator_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
