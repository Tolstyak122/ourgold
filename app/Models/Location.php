<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['arrival', 'departure'];
    protected $casts = [
        'arrival' => 'datetime',
        'departure' => 'datetime',
    ];

    public function furniture() {
        return $this->belongsTo(Furniture::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function scopeAppartmentFilter($query, ?Appartment $appartment) {
        return $query->when($appartment, function($query_scope) use ($appartment) {
            $query_scope->whereHas('room',
                                   function($query_app) use ($appartment) { $query_app->whereBelongsTo($appartment); });
        });
    }

    public function scopeRoomFilter($query, ?Room $room) {
        return $query->when($room, function($query_scope) use ($room) {
            $query_scope->whereBelongsTo($room);
        });
    }

    public function scopeArrivalFilter($query, $arrival) {
        return $query->when($arrival, function($query_scope) use ($arrival) {
            $query_scope->where('arrival', '>=', $arrival);
        });
    }

    public function scopeDepartureFilter($query, $departure) {
        return $query->when($departure, function($query_scope) use ($departure) {
            $query_scope->where('departure', '<=', $departure);
        });
    }

    public function scopePresentFilter($query, $present_date) {
        return $query->when($present_date, function($query_scope) use ($present_date) {
            $query_scope->where('arrival', '<=', $present_date)
                        ->where(function($query) use ($present_date) {
                                    $query->whereNull('departure')->orwhere('departure', '>=', $present_date);
                                });
        });
    }
}
