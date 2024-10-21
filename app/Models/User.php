<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Preference;
use App\Services\TenantDatabaseService;
use Illuminate\Support\Str;
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
        'name',
        'email',
        'password',
        'db',
        'db_user',
    ];

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
        ];
    }
    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->db = config('tenant.database_prefix') . rand(100000, 999999);
            $user->db_user = config('tenant.database_user_prefix') . Str::random(10);
            $user->db_password = Str::random(10);
        });

        static::created(function ($user) {
            app(TenantDatabaseService::class)->createTenantDatabase($user);
        });

        static::deleting(function ($user) {
            app(TenantDatabaseService::class)->deleteTenantDatabase($user);
        });
    }
}
