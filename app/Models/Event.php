<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Event",
 *     required={"title", "date", "location", "capacity", "user_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string",),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="date", type="string", format="date"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(property="capacity", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'capacity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
