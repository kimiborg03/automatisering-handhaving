<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'match_user', 'match_id', 'user_id');
    }

    protected $casts = [
        'checkin_time' => 'datetime',
        'kickoff_time' => 'datetime',
        'deadline' => 'datetime',
        'groups' => 'array',
        // add others like 'deadline' if needed
    ];
    protected $fillable = [
        'name',
        'location',
        'comment',
        'checkin_time',
        'kickoff_time',
        'category',
        'limit',
        'deadline',
        'users',
        'groups'
    ];
}
