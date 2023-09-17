<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'created_by', 'points'];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class, 'id_task', 'id');
    }
}
