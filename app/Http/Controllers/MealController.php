<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $searchKey = $request->query('searchKey');

    if ($searchKey) {
        // Perform search logic based on name
        $meals = Meal::where('name', 'LIKE', "{$searchKey}%")
                     ->orderBy('name')
                     ->get();
    } else {
        // Return all meals if no search key is provided
        $meals = Meal::all();
    }

    return response()->json([
        'message' => 'List of Meals',
        'meals' => $meals
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
                'description' => 'nullable|string',
                'calories' => 'required|numeric',
                'carbohydrate' => 'required|numeric',
                'protein' => 'required|numeric',
                'fat' => 'required|numeric',
                'sodium' => 'required|numeric',
                'volume' => 'nullable|numeric',
                'drink' => 'nullable|boolean',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]);

            $photoName = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/meal-photos');
                $photoName = basename($photoPath);
            }

            $meal = Meal::create([
                'user_id' => $validatedData['user_id'] ?? 0,
                'name' => $validatedData['name'],
                'quantity' => $validatedData['quantity'] ?? 0,
                'description' => $validatedData['description'] ?? null,
                'calories' => $validatedData['calories'],
                'carbohydrate' => $validatedData['carbohydrate'],
                'protein' => $validatedData['protein'],
                'fat' => $validatedData['fat'],
                'sodium' => $validatedData['sodium'],
                'volume' => $validatedData['volume'] ?? 0,
                'drink' => $validatedData['drink'] ?? 0,
                'photo_name' => $photoName
            ]);

            return response()->json([
                'message' => 'New Meal Added',
                'meal' => $meal
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
    public function show(Meal $meal)
    {
        return response()->json([
            'message'=>'Single Meal',
            'meal'=> $meal
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer',
                'name' => 'nullable|string|max:255',
                'quantity' => 'nullable|numeric',
                'description' => 'nullable|string',
                'calories' => 'nullable|numeric',
                'carbohydrate' => 'nullable|numeric',
                'protein' => 'nullable|numeric',
                'fat' => 'nullable|numeric',
                'sodium' => 'nullable|numeric',
                'volume' => 'nullable|numeric',
               'drink' => 'nullable|boolean',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]);

            $meal->update($validatedData);

            if ($request->hasFile('photo')) {
                if ($meal->photo_name) {
                    Storage::delete('public/meal-photos/' . $meal->photo_name);
                }

                $photoPath = $request->file('photo')->store('public/meal-photos');
                $meal->photo_name = basename($photoPath);
                $meal->save();
            }

            return response()->json([
                'message' => 'Meal updated successfully',
                'meal' => $meal
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating meal: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to update meal',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        try {
            if ($meal->photo_name) {
                Storage::delete('public/meal-photos/' . $meal->photo_name);
            }

            $meal->delete();

            return response()->json([
                'message' => 'Meal deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting meal: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to delete meal',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
