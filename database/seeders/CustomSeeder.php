<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\custom;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFilePath = database_path('json/premiumlibrary.json');

        // Check if the JSON file exists
        if (!File::exists($jsonFilePath)) {
            return;
        }

        // Read the JSON file content
        $json = File::get($jsonFilePath);

        // Decode the JSON content into an array of objects
        $customs = json_decode($json);

        // Check if decoding the JSON content was successful
        if (!$customs) {
            return;
        }

        // Iterate over each meal data
        foreach ($customs as $customData) {
            $photoName = null;

            // Check if photo attribute is set and not empty
            if (isset($customData->photo) && !empty($customData->photo)) {
                // Define the path to the seeder_photos folder
                $photoPath = base_path('storage/app/seeder_photos/' . $customData->photo);

                // Check if the photo file exists
                if (File::exists($photoPath)) {
                    // Get the base name of the photo file
                    $photoName = basename($photoPath);

                    // Store photo in public storage under 'custom-photos' directory
                    Storage::disk('public')->putFileAs('custom-photos', new \Illuminate\Http\File($photoPath), $photoName);

                    // Copy the photo to the 'meal-photos' directory
                    Storage::disk('public')->putFileAs('meal-photos', new \Illuminate\Http\File($photoPath), $photoName);
                }
            }

            // Create the custom record
            custom::create([
                'name' => $customData->name,
                'description' => $customData->description ?? null,
                'quantity' => $customData->quantity ?? 100,
                'calories' => $customData->calories,
                'carbohydrate' => $customData->carbohydrate,
                'protein' => $customData->protein,
                'fat' => $customData->fat,
                'sodium' => $customData->sodium,
                'volume' => $customData->volume ?? 0,
                'drink' => $customData->drink ?? 0,
                'photo_name' => $photoName // Ensure this matches your database column name
            ]);
        }
    }
}
