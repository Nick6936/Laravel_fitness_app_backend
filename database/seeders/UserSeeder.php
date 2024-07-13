<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // File path to the photo in your seeder_photos directory
        $photoPath = 'seeder_photos/Yujan_Bhattarai.jpg';

        // Check if the photo file exists in the 'local' disk
        if (Storage::disk('local')->exists($photoPath)) {
            // Create the user with photo
            $user = User::create([
                'name' => 'Yuzan Bhattarai',
                'age' => 24,
                'email' => 'test@gmail.com',
                'phone' => '911',
                'password' => bcrypt('hello@123'), // Hash the password
                'sex' => 'Male',
                'weight' => 65.5,
                'ethnicity' => 'Asian',
                'bodyType' => 'Slim',
                'bodyGoal' => 'Be Fit',
                'bloodPressure' => '120/80',
                'bloodSugar' => '100',
                'photo_name' => basename($photoPath) // Store the filename in photo_name column
            ]);

            // Move the photo to user's photo storage location
            Storage::disk('public')->copy($photoPath, 'user-photos/' . basename($photoPath));

            $this->command->info('User seeded successfully with photo.');
        } else {
            // Handle the case where the photo file does not exist
            $this->command->warn("Photo file '$photoPath' not found. User seeded without photo.");

            // Create the user without photo
            $user = User::create([
                'name' => 'Yuzan Bhattarai',
                'age' => 24,
                'email' => 'test@gmail.com',
                'phone' => '911',
                'password' => bcrypt('hello@123'), // Hash the password
                'sex' => 'Male',
                'weight' => 65.5,
                'ethnicity' => 'Asian',
                'bodyType' => 'Slim',
                'bodyGoal' => 'Be Fit',
                'bloodPressure' => '120/80',
                'bloodSugar' => '100',
            ]);

            $this->command->info('User seeded successfully without photo.');
        }
    }
}
