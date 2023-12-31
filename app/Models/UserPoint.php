<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $fillable = ['id_user', 'id_task', 'assigned_at', 'finished_at', 'verified_at'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function role()
    {
        return $this->task()->belongsTo(Role::class, 'type', 'id');
    }
}
