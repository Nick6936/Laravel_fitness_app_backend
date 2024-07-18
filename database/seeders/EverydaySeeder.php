<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\everyday;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class EverydaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = database_path('json/everyday.json');

        // Check if the JSON file exists
        if (!File::exists($jsonFilePath)) {
            return;
        }

        // Read the JSON file content
        $json = File::get($jsonFilePath);

        // Decode the JSON content into an array of objects
        $everydays = json_decode($json);

        // Check if decoding the JSON content was successful
        if (!$everydays) {
            return;
        }

        // Iterate over each meal data
        foreach ($everydays as $everydayData) {
            $photoName = null;

            // Check if photo attribute is set and not empty
            if (isset($everydayData->photo) && !empty($everydayData->photo)) {
                // Define the path to the seeder_photos folder
                $photoPath = base_path('storage/app/seeder_photos/' . $everydayData->photo);

                // Check if the photo file exists
                if (File::exists($photoPath)) {
                    // Get the base name of the photo file
                    $photoName = basename($photoPath);

                    // Store photo in public storage under 'custom-photos' directory
                    Storage::disk('public')->putFileAs('everyday-photos', new \Illuminate\Http\File($photoPath), $photoName);
                }
            }

            // Create the everyday record
            everyday::create([
                'name' => $everydayData->name,
                'calories' => $everydayData->calories,
                'carbohydrate' => $everydayData->carbohydrate,
                'protein' => $everydayData->protein,
                'fat' => $everydayData->fat,
                'sodium' => $everydayData->sodium,
                'photo_name' => $photoName // Ensure this matches your database column name
            ]);
        }
        // $json = File::get(path: 'database/json/everyday.json');
        // $everydays = collect(json_decode($json));
        // $everydays->each(function ($everyday) {
        //     everyday::create([
        //         'user_id' => $everyday->user_id,
        //         'name' => $everyday->name,
        //         'calories' => $everyday->calories,
        //         'carbohydrate' => $everyday->carbohydrate,
        //         'protein' => $everyday->protein,
        //         'fat' => $everyday->fat,
        //         'sodium' => $everyday->sodium,
        //     ]);
        // });
    }
}
