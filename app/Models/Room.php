<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function appartment() {
        return $this->belongsTo(Appartment::class);
    }

    public function type() {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }

    public function locations() {
        return $this->hasMany(Location::class);
    }
}
