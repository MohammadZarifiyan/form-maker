<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function queries()
    {
        return $this->hasManyThrough(Query::class, Connection::class);
    }

    public function forms()
    {
        return $this->hasManyThrough(Form::class, Project::class);
    }
}
