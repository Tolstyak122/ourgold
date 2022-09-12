<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function furnitureType() {
        return $this->belongsTo(FurnitureType::class);
    }

    public function furnitureSet() {
        return $this->belongsTo(FurnitureSet::class);
    }

    public function locations() {
        return $this->hasMany(Location::class);
    }
}
