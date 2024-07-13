<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\everyday;
use Illuminate\Support\Facades\File;

class EverydaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(path: 'database/json/everyday.json');
        $everydays = collect(json_decode($json));
        $everydays->each(function ($everyday) {
            everyday::create([
                'user_id' => $everyday->user_id,
                'name' => $everyday->name,
                'calories' => $everyday->calories,
                'carbohydrate' => $everyday->carbohydrate,
                'protein' => $everyday->protein,
                'fat' => $everyday->fat,
                'sodium' => $everyday->sodium,
            ]);
        });
    }
}
