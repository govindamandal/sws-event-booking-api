<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Booking",
 *     type="object",
 *     title="Booking",
 *     required={"id", "event_id", "attendee_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="event_id", type="integer"),
 *     @OA\Property(property="attendee_id", type="integer"),
 *     @OA\Property(
 *         property="event",
 *         ref="#/components/schemas/Event"
 *     ),
 *     @OA\Property(
 *         property="attendee",
 *         ref="#/components/schemas/Attendee"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'attendee_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }
}
