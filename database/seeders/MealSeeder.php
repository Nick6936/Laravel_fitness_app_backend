<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\meal;
use Illuminate\Support\Facades\File;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(path:'database/json/foodlibrary.json');
        $meals = collect(json_decode($json));
        $meals->each(function($meal){
            meal::create([
            'name' => $meal->name,
            'description' => $meal->description ?? null,
            'calories' => $meal->calories,
            'carbohydrate' => $meal->carbohydrate,
            'protein' => $meal->protein,
            'fat' => $meal->fat,
            'sodium' => $meal->sodium,
         ]);
        });
    }
}
