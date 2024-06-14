<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\custom;
use Illuminate\Support\Facades\File;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(path:'database/json/premiumlibrary.json');
        $customs = collect(json_decode($json));
        $customs->each(function($custom){
            custom::create([
            'name' => $custom->name,
            'user_id' => $custom->user_id ?? 0,
            'description' => $custom->description ?? null,
            'calories' => $custom->calories,
            'carbohydrate' => $custom->carbohydrate,
            'protein' => $custom->protein,
            'fat' => $custom->fat,
            'sodium' => $custom->sodium,
         ]);
        });
    }
}
