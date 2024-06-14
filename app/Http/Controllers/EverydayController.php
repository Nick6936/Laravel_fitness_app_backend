<?php

namespace App\Http\Controllers;

use App\Models\Everyday;
use App\Models\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EverydayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $everydays = Everyday::get();

        return response()->json([
            'message'=>'List of Daily Meals',
            'Meals'=> $everydays
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $everyday = new Everyday;
        $everyday->user_id = $request->user_id ?? 0;
        $everyday->name = $request->name;
        $everyday->calories = $request->calories;
        $everyday->carbohydrate = $request->carbohydrate;
        $everyday->protein = $request->protein;
        $everyday->fat = $request->fat;
        $everyday->sodium = $request->sodium;
        $everyday->save();

        return response()->json([
            'message'=>'New Daily Meal Added',
            'Meal'=> $everyday
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $everydays = Everyday::where('user_id', $user_id)->get();
        
        return response()->json([
            'message'=>'Your Daily Meals',
            'Meals'=> $everydays
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
    public function destroy($user_id)
    {
        $deletedRows = Everyday::where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Deleted all Daily Meals for user_id: ' . $user_id,
            'Deleted Meals' => $deletedRows
        ], 200);
    }

    public function saveToAnalytics($user_id)
    {
        // Calculate the sums
        $totals = Everyday::where('user_id', $user_id)
            ->select(DB::raw('
                SUM(calories) as total_calories,
                SUM(carbohydrate) as total_carbohydrate,
                SUM(protein) as total_protein,
                SUM(fat) as total_fat,
                SUM(sodium) as total_sodium
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
                'sodium' => $totals->total_sodium
            ]
        );

        return response()->json([
            'message' => 'Nutrient summary saved to analytics successfully',
            'analytic' => $analytic
        ], 200);
    }
}