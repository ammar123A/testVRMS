<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\FlRequest
 *
 * @property int $request_id
 * @property int $user_id
 * @property string|null $purpose
 * @property string|null $reservation_date
 * @property string $status
 * @property string|null $attention_to
 * @property string|null $vote_ptj
 * @property string|null $dept_faculty
 * @property string|null $officer_email
 * @property string|null $vehicle_request
 * @property int|null $no_vehicle
 * @property float|null $estimated_cost
 * @property string|null $program
 * @property string|null $booking_type
 * @property string|null $pickup_point
 * @property string|null $pickup_state
 * @property string|null $destination
 * @property string|null $destination_state
 * @property string|null $remark
 * @property string $start_date
 * @property string|null $start_time
 * @property string|null $end_date
 * @property string $end_time
 * @property string|null $agree
 * @property float|null $distance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\FlDriver|null $driver
 * @property-read \App\Models\FlVehicle|null $vehicle
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FlPassenger[] $passengers
 */
class FlRequest extends Model
{
    protected $table = 'fl_request';

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'purpose',
        'reservation_date',
        'status',
        'attention_to',
        'vote_ptj',
        'dept_faculty',
        'officer_email',
        'vehicle_request',
        'no_vehicle',
        'estimated_cost',
        'program',
        'booking_type',
        'pickup_point',
        'pickup_state',
        'destination',
        'destination_state',
        'remark',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'agree',
        'distance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(FlPassenger::class, 'request_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(FlDriver::class, 'driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(FlVehicle::class, 'vehicle_id');
    }
}
