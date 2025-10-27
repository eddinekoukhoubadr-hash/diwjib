<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model; // Utiliser le Model MongoDB
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    // Spécifie la connexion MongoDB
    protected $connection = 'mongodb';

    // Nom de la collection MongoDB (si différent du nom par défaut)
    protected $collection = 'deliveries';

    // Les champs assignables en masse
    protected $fillable = [
        'client_name',
        'address',
        'city',
        'postal_code',
        'time_slot',
        'weight',
        'status',
        'latitude',
        'longitude',
        'user_id'
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
