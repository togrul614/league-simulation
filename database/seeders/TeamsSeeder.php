<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed the teams table with sample data
        $teamsData = [
            ['name' => 'Manchester City', 'strength' => 90],
            ['name' => 'Arsenal', 'strength' => 70],
            ['name' => 'Liverpool', 'strength' => 85],
            ['name' => 'Chelsea', 'strength' => 75],
        ];

        foreach ($teamsData as $data) {
            Team::create($data);
        }
    }
}
