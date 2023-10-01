<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
