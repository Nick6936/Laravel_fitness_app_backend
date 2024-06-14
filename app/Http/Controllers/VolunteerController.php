<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volunteers = Volunteer::get();

        return response()->json([
            'message'=>'List of Users',
            'Users'=> $volunteers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $volunteer = new Volunteer;
        $volunteer->name = $request->name;
        $volunteer->age = $request->age;
        $volunteer->email = $request->email;
        $volunteer->phone = $request->phone;
        $volunteer->password = $request->password;
        $volunteer->sex = $request->sex;
        $volunteer->ethnicity = $request->ethnicity;
        $volunteer->weight = $request->weight;
        $volunteer->bodyType = $request->bodyType;
        $volunteer->bodyGoal = $request->bodyGoal;
        $volunteer->bloodPressure = $request->bloodPressure;
        $volunteer->bloodSugar = $request->bloodSugar;
        $volunteer->isPremium = $request->isPremium?? 0;
        $volunteer->save();

        return response()->json([
            'message'=>'New User Added',
            'User'=> $volunteer,
            'uid' => $volunteer->uid
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Volunteer $volunteer)
    {
        return response()->json([
            'message'=>'Requested User',
            'User'=> $volunteer
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        {
            $volunteer->name = $request->name ?? $volunteer->name;
            $volunteer->age = $request->age ?? $volunteer->age ;
            $volunteer->email = $request->email ?? $volunteer->email;
            $volunteer->phone = $request->phone ?? $volunteer->phone;
            $volunteer->password = $request->password ?? $volunteer->password;
            $volunteer->sex = $request->sex ?? $volunteer->sex;
            $volunteer->ethnicity = $request->ethnicity ?? $volunteer->ethnicity;
            $volunteer->weight = $request->weight ?? $volunteer->weight;
            $volunteer->bodyType = $request->bodyType ?? $volunteer->bodyType;
            $volunteer->bodyGoal = $request->bodyGoal ?? $volunteer->bodyGoal;
            $volunteer->bloodPressure = $request->bloodPressure ?? $volunteer->bloodPressure;
            $volunteer->bloodSugar = $request->bloodSugar ?? $volunteer->bloodSugar;
            $volunteer->isPremium = $request->isPremium?? 0;
            $volunteer->save();
    
            return response()->json([
                'message' => 'User Updated',
                'User' => $volunteer
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Volunteer $volunteer)
    {
        return response()->json([
            'message'=>'User Deleted',
            'User'=> $volunteer->delete()
        ], 200);
    }
}
