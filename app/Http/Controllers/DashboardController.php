<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller {
    public function appartments() {
        return Inertia::render('appartments');
    }
    public function locations() {
        return Inertia::render('locations', ['room' => request('room_id')]);
    }
}
