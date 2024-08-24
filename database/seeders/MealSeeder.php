<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file containing meal data
        $jsonFilePath = database_path('json/foodlibrary.json');

        // Check if the JSON file exists
        if (!File::exists($jsonFilePath)) {
            return;
        }

        // Read the JSON file content
        $json = File::get($jsonFilePath);

        // Decode the JSON content into an array of objects
        $meals = json_decode($json);

        // Check if decoding the JSON content was successful
        if (!$meals) {
            return;
        }

        // Iterate over each meal data
        foreach ($meals as $mealData) {
            $photoName = null;

            // Check if photo attribute is set and not empty
            if (isset($mealData->photo) && !empty($mealData->photo)) {
                // Define the path to the seeder_photos folder
                $photoPath = base_path('storage/app/seeder_photos/' . $mealData->photo);

                // Check if the photo file exists
                if (File::exists($photoPath)) {
                    // Get the base name of the photo file
                    $photoName = basename($photoPath);

                    // Store photo in public storage under 'meal-photos' directory
                    Storage::disk('public')->putFileAs('meal-photos', new \Illuminate\Http\File($photoPath), $photoName);
                }
            }

            // Create the meal record
            Meal::create([
                'name' => $mealData->name,
                'description' => $mealData->description ?? null,
                'quantity' => $mealData->quantity ?? 100,
                'calories' => $mealData->calories,
                'carbohydrate' => $mealData->carbohydrate,
                'protein' => $mealData->protein,
                'fat' => $mealData->fat,
                'sodium' => $mealData->sodium,
                'volume' => $mealData->volume ?? 0,
                'drink' => $mealData->drink ?? 0,
                'photo_name' => $photoName // Ensure this matches your database column name
            ]);
        }
    }
}



//Use for detecting error
// <?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Meal;
// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log;

// class MealSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         // Path to the JSON file containing meal data
//         $jsonFilePath = database_path('json/foodlibrary.json');

//         // Check if the JSON file exists
//         if (!File::exists($jsonFilePath)) {
//             Log::error("JSON file does not exist: {$jsonFilePath}");
//             return;
//         }

//         // Read the JSON file content
//         $json = File::get($jsonFilePath);

//         // Decode the JSON content into an array of objects
//         $meals = json_decode($json);

//         // Check if decoding the JSON content was successful
//         if (!$meals) {
//             Log::error("Failed to decode JSON file: {$jsonFilePath}");
//             return;
//         }

//         // Iterate over each meal data
//         foreach ($meals as $mealData) {
//             $photoName = null;

//             // Check if photo attribute is set and not empty
//             if (isset($mealData->photo) && !empty($mealData->photo)) {
//                 // Define the path to the seeder_photos folder
//                 $photoPath = base_path('storage/app/public/seeder_photos/' . $mealData->photo);

//                 // Check if the photo file exists
//                 if (File::exists($photoPath)) {
//                     // Get the base name of the photo file
//                     $photoName = basename($photoPath);

//                     try {
//                         // Store photo in public storage under 'meal-photos' directory
//                         Storage::disk('public')->putFileAs('meal-photos', new \Illuminate\Http\File($photoPath), $photoName);
//                     } catch (\Exception $e) {
//                         Log::error("Failed to store photo {$photoName}: " . $e->getMessage());
//                     }
//                 } else {
//                     // Log a warning if the photo file does not exist
//                     Log::warning("Photo file does not exist: {$photoPath}");
//                 }
//             } else {
//                 // Log a warning if the photo attribute is not set or empty
//                 Log::warning("Photo attribute is not set or empty for meal: " . json_encode($mealData));
//             }

//             // Create the meal record
//             Meal::create([
//                 'name' => $mealData->name,
//                 'description' => $mealData->description ?? null,
//                 'calories' => $mealData->calories,
//                 'carbohydrate' => $mealData->carbohydrate,
//                 'protein' => $mealData->protein,
//                 'fat' => $mealData->fat,
//                 'sodium' => $mealData->sodium,
//                 'photo_name' => $photoName // Ensure this matches your database column name
//             ]);

//             // Log info when a meal is created
//             Log::info('Meal created: ' . $mealData->name);
//         }
//     }
//} -->
