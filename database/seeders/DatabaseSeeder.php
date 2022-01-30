<?php

namespace Database\Seeders;

use App\Models\UseModel;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ThingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ThingSeeder::class,
            PlaceSeeder::class,
            UseModelSeeder::class
        ]);
    }
}
