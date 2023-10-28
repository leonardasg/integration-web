<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Freshman extends Model
{
    protected $fillable = [
        'user',
        'points',
        'level',
    ];

    public function __construct(User $user)
    {
        if (!$user->isFreshman())
        {
            return false;
        }

        parent::__construct();
        $this->user = $user;
        $this->points = $this->getPoints();
        $this->updateLevel();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getFreshmen()
    {
        $freshmen = [];
        foreach (User::all() as $user)
        {
            if ($user->isFreshman())
            {
                $freshmen[] = new Freshman($user);
            }
        }

        usort($freshmen, function($a, $b) {
            if ($a->level == $b->level) {
                return $b->points - $a->points;
            } else {
                return $b->level - $a->level;
            }
        });

        return $freshmen;
    }

    public function getTasks($verified = true, $as_type = false)
    {
        $query = '
            SELECT t.*,
                   up.*, up.id as `id_user_point`, up.`assigned_at`, up.`finished_at`, up.`verified_at`, up.`count`,
                   r.name as role_name,
                   u.`name` as created_by
            FROM `user_points` up
                INNER JOIN `tasks` t ON t.`id` = up.`id_task`
                INNER JOIN `roles` r ON r.`id` = t.`type`
                INNER JOIN `users` u ON u.`id` = t.`created_by`
            WHERE up.`id_user` = ? AND t.`type` NOT IN';

        $not_in_type = ' (' . config('custom.QUEST_ID') . ',' . config('custom.MENTOR_ID') . ')';
        $query .= $not_in_type;

        if ($verified)
        {
            $query .= ' AND up.`verified_at` IS NOT NULL';
        }

        $tasks = DB::select($query, [$this->user->getAuthIdentifier()]);
        $tasks = $this->sortTasks($tasks);

        if ($as_type)
        {
            $tasks_as_type = [];
            foreach ($tasks as $task)
            {
                $tasks_as_type[$task->type][] = $task;
            }
            return $tasks_as_type;
        }

        return $tasks;
    }

    public function getMentoringTasks($verified = true)
    {
        $query = '
            SELECT t.*, up.*, up.id as `id_user_point`, r.name as role_name, u.`name` as created_by
            FROM `user_points` up
                INNER JOIN `tasks` t ON t.`id` = up.`id_task`
                INNER JOIN `roles` r ON r.`id` = t.`type`
                INNER JOIN `users` u ON u.`id` = t.`created_by`
            WHERE up.`id_user` = ? AND t.`type` = ?';

        if ($verified)
        {
            $query .= ' AND up.verified_at IS NOT NULL';
        }

        $tasks = DB::select($query, [$this->user->getAuthIdentifier(), config('custom.MENTOR_ID')]);

        return $this->sortTasks($tasks);
    }

    public function getQuests($verified = true)
    {
        $query = '
            SELECT t.*, up.*, up.id as `id_user_point`, r.name as role_name, u.`name` as created_by
            FROM `user_points` up
                INNER JOIN `tasks` t ON t.`id` = up.`id_task`
                INNER JOIN `roles` r ON r.`id` = t.`type`
                INNER JOIN `users` u ON u.`id` = t.`created_by`
            WHERE up.`id_user` = ? AND t.`type` = ?';

        if ($verified)
        {
            $query .= ' AND up.verified_at IS NOT NULL';
        }

        $quests = DB::select($query, [$this->user->getAuthIdentifier(), config('custom.QUEST_ID')]);

        return $this->sortTasks($quests);
    }

    public function getPoints()
    {
        $tasks = $this->getTasks();
        $tasks = array_merge($tasks, $this->getMentoringTasks());

        $points = 0;
        foreach ($tasks as $row)
        {
            $points += $row->points * $row->count;
        }
        return $points;
    }

    public function updateLevel()
    {
        $levels = Level::all();
        $points = $this->points;
        $finished_quests = count($this->getQuests());

        $level = 0;
        while(
            isset($levels[$level]) &&
            $levels[$level]->points <= $points &&
            (
                ($levels[$level]->locked && $finished_quests > 0) ||
                !$levels[$level]->locked
            )
        )
        {
            if ($levels[$level]->locked)
            {
                $finished_quests--;
            }
            $level++;
        }

        $this->level = $level;
    }

    public function sortTasks($tasks)
    {
        usort($tasks, function($a, $b) {
            if (isset($a->finished_at) && isset($b->finished_at)) {
                return $b->assigned_at < $a->assigned_at;
            }
            elseif(isset($b->finished_at)) {
                return false;
            }
            elseif(isset($a->finished_at)) {
                return true;
            }
            else {
                return $b->assigned_at < $a->assigned_at;
            }
        });

        return $tasks;
    }
}
