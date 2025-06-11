<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'group_id',
        'role',
        'access',
        'password_setup_token',
    ];

    protected $hidden = [
        'password',
    ];

    public function sendPasswordResetNotification($token)
{
    $this->notify(new \App\Notifications\CustomResetPassword($token));
}
}
