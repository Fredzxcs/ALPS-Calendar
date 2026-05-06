<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Training;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // FULLNAME
        'name',
        // ACCOUNT DETAILS
        'email',
        'username',
        'usertype',
        'contact_number',
        'image',
        'color',
        'password',
        // Google OAuth
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'google_connected_at',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class, 'facilitator_id');
    }

    public function unavailabilities()
    {
        return $this->hasMany(Unavailability::class, 'user_id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google_token_expires_at' => 'datetime',
            'google_connected_at' => 'datetime',
        ];
    }

    // Mutators to encrypt/decrypt tokens
    public function setGoogleAccessTokenAttribute($value)
    {
        $this->attributes['google_access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getGoogleAccessTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setGoogleRefreshTokenAttribute($value)
    {
        $this->attributes['google_refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getGoogleRefreshTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function markGoogleConnected(array $token)
    {
        $this->google_id = $token['id'] ?? $this->google_id;
        if (isset($token['access_token'])) {
            $this->google_access_token = $token['access_token'];
        }
        if (isset($token['refresh_token'])) {
            $this->google_refresh_token = $token['refresh_token'];
        }
        if (isset($token['expires_in'])) {
            $this->google_token_expires_at = Carbon::now()->addSeconds($token['expires_in']);
        }
        $this->google_connected_at = Carbon::now();
        $this->save();
    }
}
