<?php

namespace App\Http\Controllers;

use App\Models\Everyday;
use App\Models\User;
use App\Models\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class EverydayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $everydays = Everyday::get();

        return response()->json([
            'message' => 'List of Daily Meals',
            'Meals' => $everydays
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'user_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|numeric',
            'calories' => 'required|numeric',
            'carbohydrate' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
            'sodium' => 'required|numeric',
            'volume' => 'nullable|numeric',
            'food' => 'nullable|boolean',
            'drink' => 'nullable|boolean',
            'photo_name' => 'nullable|string'
        ]);

        $photoName = $validatedData['photo_name'] ?? null;

        if ($photoName) {
            $mealPhotosPath = storage_path('app/public/meal-photos/' . $photoName);
            $everydayPhotosPath = storage_path('app/public/everyday-photos/' . $photoName);

            if (file_exists($mealPhotosPath)) {
                Storage::copy('public/meal-photos/' . $photoName, 'public/everyday-photos/' . $photoName);
            } elseif (!file_exists($everydayPhotosPath)) {
                return response()->json([
                    'error' => 'Photo not found in meal-photos or everyday-photos directories'
                ], 404);
            }
        }

        $everyday = Everyday::create([
            'user_id' => $validatedData['user_id'] ?? 0,
            'name' => $validatedData['name'],
            'quantity' => $validatedData['quantity'] ?? 0,
            'calories' => $validatedData['calories'],
            'carbohydrate' => $validatedData['carbohydrate'],
            'protein' => $validatedData['protein'],
            'fat' => $validatedData['fat'],
            'sodium' => $validatedData['sodium'],
            'volume' => $validatedData['volume'] ?? 0,
            'food' => $validatedData['food'] ?? 0, 
            'drink' => $validatedData['drink'] ?? 0,
            'photo_name' => $photoName
        ]);

        return response()->json([
            'message' => 'New Meal Added',
            'meal' => $everyday
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error adding meal: ' . $e->getMessage());

        return response()->json([
            'error' => 'Failed to add new meal',
            'message' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $everydays = Everyday::where('user_id', $user_id)->get();

        return response()->json([
            'message' => 'Your Daily Meals',
            'Meals' => $everydays
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Everyday $everyday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Everyday $everyday)
    {
        if ($everyday->photo_name) {
            Storage::delete('public/everyday-photos/' . $everyday->photo_name);
        }
        return response()->json([
            'message' => 'Meal Deleted',
            'Delete Success?' => $everyday->delete(),
            'Deleted Meal' => $everyday,
        ], 200);
    }

   // code to destroy all user specific data from everyday table. phased out as saveAnalyticsAndClearMeals() will do it for you
    public function destroyAll($user_id)
    {
        $deletedRows = Everyday::where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Deleted all Daily Meals for user_id: ' . $user_id,
            'Deleted Meals' => $deletedRows
        ], 200);
    }


    // automated refresh method(doesnt work now)
    // public function saveAnalyticsAndClearMeals()
    // {
    //     // Get all user IDs
    //     $userIds = User::pluck('user_id');

    //     // Iterate through each user ID
    //     foreach ($userIds as $userId) {
    //         // Calculate totals for the user's daily meals
    //         $totals = Everyday::where('user_id', $userId)
    //             ->select(DB::raw('
    //                 SUM(calories) as total_calories,
    //                 SUM(carbohydrate) as total_carbohydrate,
    //                 SUM(protein) as total_protein,
    //                 SUM(fat) as total_fat,
    //                 SUM(sodium) as total_sodium
    //                 SUM(volume) as total_volume
    //             '))
    //             ->first();

    //         // Create a new record in the analytics table
    //         Analytic::create([
    //             'user_id' => $userId,
    //             'calories' => $totals->total_calories ?? 0,
    //             'carbohydrate' => $totals->total_carbohydrate ?? 0,
    //             'protein' => $totals->total_protein ?? 0,
    //             'fat' => $totals->total_fat ?? 0,
    //             'sodium' => $totals->total_sodium ?? 0,
    //             'volume' => $totals->total_volume ?? 0
    //         ]);

    //         // Delete all daily meals for the current user
    //         Everyday::where('user_id', $userId)->delete();
    //     }

    //     return response()->json([
    //         'message' => 'Analytics created and daily meals cleared for all users.'
    //     ], 200);
    // }

    //code to calculate sum of everyday table and send the data to analytics. phased out as saveAnalyticsAndClearMeals() will do it for you
    public function saveToAnalytics($user_id, Request $request)
    {
        // Calculate the sums
        $totals = Everyday::where('user_id', $user_id)
            ->select(DB::raw('
                SUM(calories) as total_calories,
                SUM(carbohydrate) as total_carbohydrate,
                SUM(protein) as total_protein,
                SUM(fat) as total_fat,
                SUM(sodium) as total_sodium,
                SUM(volume) as total_volume
            '))
            ->first();

        // Create or update the record in the analytics table
        $analytic = Analytic::create(

            [
                'user_id' => $user_id,
                'calories' => $totals->total_calories,
                'carbohydrate' => $totals->total_carbohydrate,
                'protein' => $totals->total_protein,
                'fat' => $totals->total_fat,
                'sodium' => $totals->total_sodium,
                'volume' => $totals->total_volume ?? 0,
                'steps' => $request->steps ?? 0,
            ]
        );

        // Delete all daily meals for the specified user
        Everyday::where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Nutrient summary and Step Count saved to analytics successfully',
            'analytic' => $analytic
        ], 200);
    }

    public function getUserTotals($user_id)
    {
        // Calculate the sum of all specified columns for the specified user
        $totals = Everyday::where('user_id', $user_id)
            ->select(DB::raw('
                SUM(calories) as total_calories,
                SUM(carbohydrate) as total_carbohydrate,
                SUM(protein) as total_protein,
                SUM(fat) as total_fat,
                SUM(sodium) as total_sodium,
                SUM(volume) as total_volume
            '))
            ->first();

        return [
            'Total Calories' => $totals->total_calories ?? 0,
            'Total Carbohydrate' => $totals->total_carbohydrate ?? 0,
            'Total Protein' => $totals->total_protein ?? 0,
            'Total Fat' => $totals->total_fat ?? 0,
            'Total Sodium' => $totals->total_sodium ?? 0,
            'Total Volume' => $totals->total_volume ?? 0
        ];
    }

    public function getHome($user_id)
    {
        $totals = $this->getUserTotals($user_id);

        return response()->json([
            'message' => 'Today\'s Nutrient Intake',
            'data' => $totals
        ], 200);
    }
}
