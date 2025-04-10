<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Attendee",
 *     type="object",
 *     title="Attendee",
 *     required={"name", "email"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */
class Attendee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
