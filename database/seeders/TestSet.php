<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $furniture_types = array_map(fn($item) => \App\Models\FurnitureType::query()->firstOrCreate(['title' => $item]),
                                     ['wooden', 'iron', 'copper', 'green', 'blue', 'red']);
        $furniture_sets = array_map(fn($item) => \App\Models\FurnitureSet::query()->firstOrCreate(['title' => $item]),
                                    ['table', 'chair', 'cabinet', 'bed', 'sofa']);
        $room_types = array_map(fn($item) => \App\Models\RoomType::query()->firstOrCreate(['title' => $item]),
                                ['kitchen', 'bedroom', 'hall', 'restroom']);
        
        $furnitures = [];
        foreach($furniture_sets as $f_set) {
            foreach($furniture_types as $f_type) {
                $furniture = \App\Models\Furniture::query()
                                                    ->whereBelongsTo($f_set)
                                                    ->whereBelongsTo($f_type)
                                                    ->firstOrNew([], ['title' => "{$f_type->title} {$f_set->title}"]);
                if(!$furniture->exists) {
                    $furniture->furnitureSet()->associate($f_set);
                    $furniture->furnitureType()->associate($f_type);
                    $furniture->save();
                }

                $furnitures[] = $furniture;
            }
        }
        
        $types_count = count($room_types);
        $furnitures_count = count($furnitures);
        for($idx = 0; $idx < 20; $idx++) {
            $appartment  = \App\Models\Appartment::query()->create(['title' => fake()->name()]);
            $rooms_count = rand(3, 8);

            for($idx_room = 0; $idx_room < $rooms_count; $idx_room++) {
                $type = $room_types[rand(0, $types_count - 1)];
                $room = new \App\Models\Room;
                $room->type()->associate($type);
                $room->appartment()->associate($appartment);
                $room->title = $type->title . ' - ' . $idx_room;
                $room->save();

                $furniture_count = rand(2, 5);
                for($idx_furniture = 0; $idx_furniture < $furniture_count; $idx_furniture++) {
                    $location = new \App\Models\Location;
                    $location->room()->associate($room);
                    $location->furniture()->associate($furnitures[rand(0, $furnitures_count - 1)]);
                    $location->arrival   = fake()->dateTimeBetween('-90 days', '-30 days');
                    $location->departure = $location->arrival->format('w') == 3 ?
                                            null : fake()->dateTimeBetween('-29 days', '+3 days');
                    $location->save();
                }
            }
        }
    }
}
