<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meals = Meal::get();

        return response()->json([
            'message'=>'List of Meals',
            'meals'=> $meals
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $meal = new Meal;
        $meal->user_id = $request->user_id ?? 0;
        $meal->name = $request->name;
        $meal->description = $request->description ?? NULL;
        $meal->calories = $request->calories;
        $meal->carbohydrate = $request->carbohydrate;
        $meal->protein = $request->protein;
        $meal->fat = $request->fat;
        $meal->sodium = $request->sodium;
        $meal->volume = $request->volume ?? 0;
        $meal->save();

        return response()->json([
            'message'=>'New Meal Added',
            'meal'=> $meal
        ], 200);
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
        $meal->user_id = $request->user_id ?? $meal->user_id;
        $meal->name = $request->name ?? $meal->name;
        $meal->description = $request->description?? NULL ?? $meal->name;
        $meal->calories = $request->calories ?? $meal->calories;
        $meal->carbohydrate = $request->carbohydrate ?? $meal->carbohydrate;
        $meal->protein = $request->protein ?? $meal->protein;
        $meal->fat = $request->fat ?? $meal->fat;
        $meal->sodium = $request->sodium?? $meal->sodium;
        $meal->volume = $request->volume?? $meal->volume;
        $meal->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        //
    }
}
