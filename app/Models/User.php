<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
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

    public function isAdmin()
    {
        return $this->hasRole('site-admin');
    }

    public function isMember()
    {
        return $this->hasRole('member');
    }

    public function isFreshman()
    {
        return $this->hasRole('freshman');
    }

    public function isCoordinator($type = null)
    {
        if (!$this->hasRole('coordinator'))
        {
            return false;
        }

        if (!isset($type))
        {
            return true;
        }

        $role = Role::where('id', $type)->get()->first()->getAttributes()['name'];
        return $this->hasRole($role);
    }

    public function isZIKCoordinator()
    {
        return $this->isCoordinator(config('custom.ZIK_ID'));
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
        if (auth()->user()->isZIKCoordinator() || auth()->user()->isAdmin())
        {
            $options[] = [
                'id' => config('custom.MENTOR_ID'),
                'name' => 'MENTORING',
            ];
        }

        return $options;
    }

    public function getRolesAsType()
    {
        $roles = [];
		if (auth()->user()->isAdmin())
		{
			foreach (Role::where('as_type', true)->get() as $role)
			{
				$roles[] = $role->getAttributes();
			}
			return $roles;
		}

        foreach ($this->roles()->where('as_type', true)->get()->all() as $role)
        {
            $roles[] = $role->getAttributes();
        }
        return $roles;
    }

    public function canEditTask($task)
    {
        return (
            $task->created_by == auth()->user()->getAuthIdentifier() ||
            auth()->user()->isAdmin() ||
            auth()->user()->isCoordinator($task->type) ||
            $task->type == config('custom.QUEST_ID')
        );
    }

    public function getCreatedTasks($with_assigned = true)
    {
        $query = '
            SELECT t.*, r.name as role_name
            FROM `tasks` t
                INNER JOIN `roles` r ON r.`id` = t.`type`
            WHERE t.`created_by` = ? AND t.`type` != ?';

        $tasks = DB::select($query, [$this->getAuthIdentifier(), config('custom.QUEST_ID')]);

        foreach ($tasks as $task)
        {
            if ($with_assigned)
            {
                $task_obj = Task::find($task->id);
                $task->assigned_to = $task_obj->getAssigned();
            }
            $task->created_by = $this->getAuthIdentifier();
        }

        return $tasks;
    }
}
