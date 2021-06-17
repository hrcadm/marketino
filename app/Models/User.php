<?php

namespace App\Models;

# use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable # implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function groups()
    {
        return $this->hasMany('App\Models\UserGroup');
    }


    public function assignGroups($groups)
    {
        $userGroups = [];
        foreach ($groups as $group) {
            $userGroups[] = new UserGroup(['name' => $group]);
        }

        return $this->groups()->saveMany($userGroups);
    }

    public function syncGroups($groups)
    {
        $this->groups()->delete();
        return $this->assignGroups($groups);
    }

    public function hasGroup($group)
    {
        return $this->groups()->where('name', $group)->exists();
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_agent_id', 'id');
    }
}
