<?php

namespace App\Http\Controllers;

use App\Models\Custom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customs = Custom::get();

        return response()->json([
            'message'=>'List of Premium Meals',
            'Meals'=> $customs
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
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
                $photoPath = $request->file('photo')->store('public/custom-photos');
                $photoName = basename($photoPath);

                // Copy the photo to the meal-photos directory
            Storage::copy('public/custom-photos/' . $photoName, 'public/meal-photos/' . $photoName);
            }

            $custom = Custom::create([
                'user_id' => $validatedData['user_id'] ?? 0,
                'name' => $validatedData['name'],
                'quantity' => $validatedData['quantity'] ?? 100,
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
                'meal' => $custom
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
    public function show(Request $request, $user_id)
    {
        $searchKey = $request->query('searchKey');

        if ($searchKey) {
            // Perform search logic based on name
            $customs = Custom::where('user_id', $user_id)
                             ->where('name', 'LIKE', "{$searchKey}%")
                             ->orderBy('name')
                             ->get();
        } else {
            $customs = Custom::where('user_id', $user_id)->get();
        }

        return response()->json([
            'message' => 'Requested Meals',
            'Meals' => $customs
        ], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Custom $custom)
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

            $custom->update($validatedData);

            if ($request->hasFile('photo')) {
                if ($custom->photo_name) {
                    Storage::delete('public/custom-photos/' . $custom->photo_name);
                }

                $photoPath = $request->file('photo')->store('public/custom-photos');
                $custom->photo_name = basename($photoPath);
                $custom->save();
            }

            return response()->json([
                'message' => 'Meal updated successfully',
                'meal' => $custom
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
    public function destroy(Custom $custom)
    {
        // Delete user's photo from storage if it exists
    if ($custom->photo_name) {
        Storage::delete('public/custom-photos/' . $custom->photo_name);
    }
        return response()->json([
            'message'=>'Meal Deleted',
            'Delete Sucess?'=> $custom->delete(),
            'Deleted Meal'=> $custom
            
        ], 200);
    }
}
