<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'trip_id',
        'date',
        'vehicle',
        'number_of_passengers',
        'number_of_bags',
        'names',
        'passport_photos',
        'id_photos',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'names' => 'array',
        'passport_photos' => 'array',
        'id_photos' => 'array',
        'number_of_passengers' => 'integer',
        'number_of_bags' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
