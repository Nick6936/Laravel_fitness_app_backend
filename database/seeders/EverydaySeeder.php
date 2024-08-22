<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Everyday;
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

            // Check if photo_name attribute is set and not empty
            if (isset($everydayData->photo_name) && !empty($everydayData->photo_name)) {
                // Define the path to the seeder_photos folder
                $photoPath = base_path('storage/app/seeder_photos/' . $everydayData->photo_name);

                // Check if the photo file exists
                if (File::exists($photoPath)) {
                    // Get the base name of the photo file
                    $photoName = basename($photoPath);

                    // Store photo in public storage under 'custom-photos' directory
                    Storage::disk('public')->putFileAs('everyday-photos', new \Illuminate\Http\File($photoPath), $photoName);
                }
            }

            // Create the everyday record
            Everyday::create([
                'user_id' => $everydayData->user_id,
                'name' => $everydayData->name,
                'quantity' => $everydayData->quantity ?? 0,
                'calories' => $everydayData->calories,
                'carbohydrate' => $everydayData->carbohydrate,
                'protein' => $everydayData->protein,
                'fat' => $everydayData->fat,
                'sodium' => $everydayData->sodium,
                'volume' => $everydayData->volume ?? 0,
                'drink' => $everydayData->drink ?? 0,
                'photo_name' => $photoName
            ]);
        }
    }
}
