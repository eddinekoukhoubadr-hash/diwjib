<?php

namespace App\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable; // <- changement pour MongoDB
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The MongoDB connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb'; // connexion MongoDB

    /**
     * The MongoDB collection to use.
     *
     * @var string
     */
    protected $collection = 'users'; // nom de la collection

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // si tu veux gérer les rôles
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return isset($this->role) && $this->role === 'admin';
    }
}
