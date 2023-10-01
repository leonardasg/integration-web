<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'as_type', 'id_user', 'id_role'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'type', 'id');
    }
}
