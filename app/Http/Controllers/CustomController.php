<?php

namespace App\Http\Controllers;

use App\Models\Custom;
use Illuminate\Http\Request;

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
        $custom = new custom;
        $custom->user_id = $request->user_id ?? 0;
        $custom->name = $request->name;
        $custom->description = $request->description?? NULL;
        $custom->calories = $request->calories;
        $custom->carbohydrate = $request->carbohydrate;
        $custom->protein = $request->protein;
        $custom->fat = $request->fat;
        $custom->sodium = $request->sodium;
        $custom->volume = $request->volume ?? 0;
        $custom->save();

        return response()->json([
            'message'=>'New Premium Meal Added',
            'Meal'=> $custom
        ], 200);
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
        // Validate the incoming request
        $validatedData = $request->validate([
            'user_id' => 'sometimes|integer',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'calories' => 'sometimes|numeric',
            'carbohydrate' => 'sometimes|numeric',
            'protein' => 'sometimes|numeric',
            'fat' => 'sometimes|numeric',
            'sodium' => 'sometimes|numeric',
            'volume' => 'sometimes|numeric',
        ]);

       // Fill the custom model with validated data, only updating provided fields
       $custom->fill($validatedData);
        
       // Save the changes to the database
       $custom->save();

        return response()->json([
            'message' => 'Meal Updated',
            'Meal' => $custom
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Custom $custom)
    {
        return response()->json([
            'message'=>'Meal Deleted',
            'Delete Sucess?'=> $custom->delete(),
            'Deleted Meal'=> $custom
            
        ], 200);
    }
}
