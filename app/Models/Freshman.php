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
        if (!$user->hasRole('freshman'))
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
            if ($user->hasRole('freshman'))
            {
                $freshmen[] = new Freshman($user);
            }
        }
        return $freshmen;
    }

    public function getTasks($verified = true)
    {
        $query = '
            SELECT t.*, up.*, up.id as `id_user_point`, r.name as role_name, u.`name` as created_by
            FROM `user_points` up
                INNER JOIN `tasks` t ON t.`id` = up.`id_task`
                INNER JOIN `roles` r ON r.`id` = t.`type`
                INNER JOIN `users` u ON u.`id` = t.`created_by`
            WHERE up.`id_user` = ? AND t.`type` != ?';

        if ($verified)
        {
            $query .= ' AND up.verified_at IS NOT NULL';
        }

        return DB::select($query, [$this->user->getAuthIdentifier(), config('custom.QUEST_ID')]);
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

        return DB::select($query, [$this->user->getAuthIdentifier(), config('custom.QUEST_ID')]);
    }

    public function getPoints()
    {
        $tasks = $this->getTasks();

        $points = 0;
        foreach ($tasks as $row)
        {
            $points += $row->points;
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
}
