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
        $custom->user_id = $request->user_id ?? $custom->user_id;
        $custom->name = $request->name ?? $custom->name;
        $custom->description = $request->description ?? $custom->description;
        $custom->calories = $request->calories ?? $custom->calories;
        $custom->carbohydrate = $request->carbohydrate ?? $custom->carbohydrate;
        $custom->protein = $request->protein ?? $custom->protein;
        $custom->fat = $request->fat ?? $custom->fat;
        $custom->sodium = $request->sodium?? $custom->sodium;
        $custom->volume = $request->volume?? $custom->volume;
        $custom->save();

        return response()->json([
            'message'=>'Meal Updated',
            'Meal'=> $custom
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Custom $custom)
    {
        return response()->json([
            'message'=>'Meal Deleted',
            'meal'=> $custom->delete()
        ], 200);
    }
}
