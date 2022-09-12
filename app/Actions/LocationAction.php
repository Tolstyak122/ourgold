<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Http\Request;

class LocationAction {

    private $filters;

    public function __construct(Request $filters) {
        $this->filters = $filters;
    }

    public function index() {

        $appartment = \App\Models\Appartment::find($this->filters->query('appartment'));
        $room = \App\Models\Room::find($this->filters->query('room'));
        $arrival = $this->filters->query('arrival') ? Carbon::parse($this->filters->query('arrival')) : null;
        $departure = $this->filters->query('departure') ? Carbon::parse($this->filters->query('departure')) : null;
        $present_date = $this->filters->query('present_date') ? Carbon::parse($this->filters->query('present_date')) : null;
        
        return \App\Models\Location::appartmentFilter($appartment)
                                        ->roomFilter($room)
                                        ->arrivalFilter($arrival)
                                        ->departureFilter($departure)
                                        ->presentFilter($present_date)
                                        ->get();
    }
}