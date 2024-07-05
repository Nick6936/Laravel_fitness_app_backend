<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([

            'name' =>'Yuzan Bhattarai',
            'age' =>24,
            'email' =>'test@gmailcom',
            'phone' =>'911',
            'password' =>'hello@123',
            'sex' =>'Male',
            'weight' =>65.5,
            'ethnicity' =>'Asian',
            'bodyType' =>'Slim',
            'bodyGoal' =>'Be Fit',
            'bloodPressure' =>'120/80',
            'bloodSugar' =>'100',
            
        ]);
    }
}
