<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appartment;
use App\Models\RoomType;
use App\Models\FurnitureSet;
use App\Models\FurnitureType;
use App\Actions\LocationAction;
use App\Models\Furniture;

class ApiController extends Controller {

    public function locations(Request $request) {
        return response()->json(\App::make(LocationAction::class)->index());
    }

    public function appartments() {
        return Appartment::query()->with(['rooms'])->get();
    }

    public function room_types() {
        return response()->json(RoomType::all(['id', 'title']));
    }

    public function furnitures() {
        return response()->json(Furniture::all(['id', 'title', 'furniture_set_id', 'furniture_type_id']));
    }

    public function furniture_sets() {
        return response()->json(FurnitureSet::all(['id', 'title']));
    }

    public function furniture_types() {
        return response()->json(FurnitureType::all(['id', 'title']));
    }
}
