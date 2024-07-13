<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\analytic;
use Illuminate\Support\Facades\File;

class AnalyticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(path: 'database/json/analytic.json');
        $analytics = collect(json_decode($json));
        $analytics->each(function ($analytic) {
            analytic::create([
                'user_id' => $analytic->user_id,
                'calories' => $analytic->calories,
                'carbohydrate' => $analytic->carbohydrate,
                'protein' => $analytic->protein,
                'fat' => $analytic->fat,
                'sodium' => $analytic->sodium,
                'volume' => $analytic->volume,
                'steps' => $analytic->steps,
                'created_at' => $analytic->created_at,
                'updated_at' => $analytic->updated_at,
            ]);
        });
    }
}
