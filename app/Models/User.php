<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = true;

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
        return $this->belongsToMany(Role::class, 'role_user', 'id_user', 'id_role');
    }

    public function updateRoles($roles)
    {
        if (!$this->roles()->sync($roles)) {
            return false;
        }

        $roles_inserted = $this->roles()->allRelatedIds()->all();
        $current_date = date('Y-m-d H:i:s');
        $results = DB::select('SELECT * FROM `role_user` WHERE `id_role` IN (?) AND `id_user` = ?', [implode(',', $roles_inserted), $this->getAuthIdentifier()]);
        foreach ($results as $role)
        {
            if (empty($role->created_at))
            {
                DB::update('UPDATE `role_user` SET `created_at`= ? WHERE `id_role`= ? AND `id_user` = ?', [$current_date, $role->id, $this->getAuthIdentifier()]);
            }
            DB::update('UPDATE `role_user` SET `updated_at`= ? WHERE `id_role`= ? AND `id_user` = ?', [$current_date, $role->id, $this->getAuthIdentifier()]);
        }

        return true;
    }
}
