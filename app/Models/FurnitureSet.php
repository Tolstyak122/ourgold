<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurnitureSet extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function furnitures() {
        return $this->hasMany(Furniture::class);
    }
}
