<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'trip_id',
        'pickup_location',
        'destination',
        'number_of_passengers',
        'number_of_bags_of_wieght_10',
        'number_of_bags_of_wieght_23',
        'number_of_bags_of_wieght_30',
        'date',
        'vehicle',
        'name',
        'entry_requirement',
        'passport_photo',
        'ticket_photo',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'number_of_passengers' => 'integer',
        'number_of_bags_of_wieght_10' => 'integer',
        'number_of_bags_of_wieght_23' => 'integer',
        'number_of_bags_of_wieght_30' => 'integer',
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
