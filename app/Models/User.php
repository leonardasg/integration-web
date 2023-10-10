<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class, 'id_user', 'id');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function getRoles($role = null)
    {
        if (isset($role))
        {
            return [$this->roles()->where('name', $role)->get()->get(0)->getAttributes()];
        }

        $roles_array = [];
        $roles = $this->roles()->get()->all();
        foreach ($roles as $role)
        {
            $roles_array[] = $role->getAttributes();
        }

        return $roles_array;
    }

    public function getRolesName($role = null)
    {
        $roles = $this->getRoles($role);

        $roles_name = '';
        foreach ($roles as $role)
        {
            $roles_name .= ucfirst($role['name']) . '/';
        }
        $roles_name = rtrim($roles_name, '/');

        return $roles_name;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'id_user', 'id_role')->withTimestamps();
    }

    public function updateRoles($roles)
    {
        if (!$this->roles()->sync($roles)) {
            return false;
        }

        return true;
    }

    public function getRolesAsOptions()
    {
        $roles = $this->getRolesAsType();

        $options = [];
        foreach ($roles as $role) {
            $options[] = [
                'id' => $role['id'],
                'name' => strtoupper($role['name']),
            ];
        }
        $options[] = [
            'id' => config('custom.QUEST_ID'),
            'name' => 'QUEST',
        ];

        return $options;
    }

    public function getRolesAsType()
    {
        $roles = [];
        foreach ($this->roles()->where('as_type', true)->get()->all() as $role)
        {
            $roles[] = $role->getAttributes();
        }
        return $roles;
    }
}
