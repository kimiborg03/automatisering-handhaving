<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $fillable = [
        'name',
        'location',
        'comment',
        'checkin_time',
        'kickoff_time',
        'category',
        'limit',
        'deadline',
        'users'
    ];
}
