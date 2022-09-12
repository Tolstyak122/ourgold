<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Appartment extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function rooms() {
        return $this->hasMany(Room::class);
    }
}
