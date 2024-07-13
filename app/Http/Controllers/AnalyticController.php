<?php

namespace App\Http\Controllers;

use App\Models\Analytic;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $analytics = Analytic::get();

        return response()->json([
            'message' => 'Your Recorded Data',
            'Datas' => $analytics
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $analytic = new Analytic;
        $analytic->user_id = $request->user_id;
        $analytic->calories = $request->calories;
        $analytic->carbohydrate = $request->carbohydrate;
        $analytic->protein = $request->protein;
        $analytic->fat = $request->fat;
        $analytic->sodium = $request->sodium;
        $analytic->volume = $request->volume;
        $analytic->save();

        return response()->json([
            'message' => 'New Data Added',
            'Data' => $analytic
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $analytics = Analytic::where('user_id', $user_id)->get();

        return response()->json([
            'message' => 'Your Accumulated Records',
            'Records' => $analytics
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Analytic $analytic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $deletedRows = Analytic::where('user_id', $user_id)->delete();

        return response()->json([
            'message' => 'Deleted all Accumulated Records for user_id: ' . $user_id,
            'Deleted Records' => $deletedRows
        ], 200);
    }

    public function destroyAll(Analytic $analytic)
    {
        Analytic::truncate();

        return response()->json([
            'message' => 'All Data Deleted'
        ], 200);
    }
}
