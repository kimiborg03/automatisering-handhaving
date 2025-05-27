<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $fillable = ['name'];

     public function users()
    {
        return $this->hasMany(User::class, 'group_id');
    }
}

