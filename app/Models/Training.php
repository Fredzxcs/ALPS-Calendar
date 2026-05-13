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
        'conference_link',
        'mode',
        'location',
        'account_id',
        'need_transportation',
        'outbound_pickup_time',
        'outbound_contact_number',
        'outbound_pickup_location',
        'outbound_dropoff_location',
        'return_trip_needed',
        'return_pickup_time',
        'return_contact_number',
        'return_pickup_location',
        'return_dropoff_location',
        'notify_coordinator',
        'coordinator_to_notify',
        'is_updated',
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

    /**
     * Get assistants as a collection of User models.
     * The 'assistant' column stores comma-separated user IDs.
     */
    public function getAssistantsAttribute()
    {
        if (empty($this->attributes['assistant'] ?? null)) {
            return collect();
        }

        $assistantIds = array_filter(
            array_map('trim', explode(',', $this->attributes['assistant']))
        );

        if (empty($assistantIds)) {
            return collect();
        }

        return User::whereIn('id', $assistantIds)->get();
    }

    /**
     * Get the company name, either from the custom field or the related company.
     */
    public function getCompanyNameAttribute()
    {
        // If company_name is explicitly set (for "other" companies), return it
        if (!empty($this->attributes['company_name'] ?? null)) {
            return $this->attributes['company_name'];
        }

        // Otherwise, try to get it from the related company
        if ($this->company) {
            return $this->company->company_name;
        }

        return null;
    }


}

