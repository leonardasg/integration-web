<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'created_by', 'points', 'type', 'date_from', 'date_to', 'active'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'type', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class, 'id_task', 'id');
    }

    public static function getTasks($with_assigned = false)
    {
        $tasks = Task::with('user', 'role')->where('type', '!=', config('custom.QUEST_ID'))->get();
        if (!$with_assigned)
        {
            return $tasks;
        }

        foreach ($tasks as $task)
        {
            $task['assigned_to'] = $task->getAssigned();
        }

        return $tasks;
    }

    public static function getQuests($with_assigned = false, $verified = false)
    {
        $quests = Task::with('user', 'role')->where('type', '=', config('custom.QUEST_ID'))->get();

        if (!$with_assigned)
        {
            return $quests;
        }

        foreach ($quests as $quest)
        {
            $quest['assigned_to'] = $quest->getAssigned();
        }

        return $quests;
    }

    public function getAssigned()
    {
        return DB::select('
            SELECT up.`id` as `id_user_point`, up.`assigned_at`, up.`finished_at`, up.`verified_at`,
                   u.`id` as `id_user`, u.`name` as `user_name`
            FROM `user_points` up
                INNER JOIN `users` u ON u.`id` = up.`id_user`
            WHERE up.id_task = ?', [$this->id]);
    }
}
