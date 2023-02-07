<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Vehicle;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parking extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
    protected $fillable = ['user_id', 'vehicle_id', 'zone_id', 'start_time', 'end_time', 'total_price'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
