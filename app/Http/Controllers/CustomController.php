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
    public function index()
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
                'description' => 'nullable|string',
                'calories' => 'required|numeric',
                'carbohydrate' => 'required|numeric',
                'protein' => 'required|numeric',
                'fat' => 'required|numeric',
                'sodium' => 'required|numeric',
                'volume' => 'nullable|numeric',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]);

            $photoName = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/custom-photos');
                $photoName = basename($photoPath);
            }

            $custom = Custom::create([
                'user_id' => $validatedData['user_id'] ?? 0,
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'calories' => $validatedData['calories'],
                'carbohydrate' => $validatedData['carbohydrate'],
                'protein' => $validatedData['protein'],
                'fat' => $validatedData['fat'],
                'sodium' => $validatedData['sodium'],
                'volume' => $validatedData['volume'] ?? 0,
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
    public function show($user_id)
    {
        $customs = Custom::where('user_id', $user_id)->get();
        //$customs = Custom::whereIn('user_id', [$user_id, 0])->get();
        
        return response()->json([
            'message'=>'Requested Meals',
            'Meals'=> $customs
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
                'description' => 'nullable|string',
                'calories' => 'nullable|numeric',
                'carbohydrate' => 'nullable|numeric',
                'protein' => 'nullable|numeric',
                'fat' => 'nullable|numeric',
                'sodium' => 'nullable|numeric',
                'volume' => 'nullable|numeric',
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
