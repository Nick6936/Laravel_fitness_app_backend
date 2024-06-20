<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();

        return response()->json([
            'message'=>'List of Users',
            'Users'=> $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->name ?? $user->name;
        $user->age = $request->age ?? $user->age ;
        $user->email = $request->email ?? $user->email;
        $user->phone = $request->phone ?? $user->phone;
        $user->password = $request->password ?? $user->password;
        $user->sex = $request->sex ?? $user->sex;
        $user->ethnicity = $request->ethnicity ?? $user->ethnicity;
        $user->weight = $request->weight ?? $user->weight;
        $user->bodyType = $request->bodyType ?? $user->bodyType;
        $user->bodyGoal = $request->bodyGoal ?? $user->bodyGoal;
        $user->bloodPressure = $request->bloodPressure ?? $user->bloodPressure;
        $user->bloodSugar = $request->bloodSugar ?? $user->bloodSugar;
        $user->isPremium = $request->isPremium?? 0;
        $user->save();

        return response()->json([
            'message' => 'User Updated',
            'User' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return response()->json([
            'message'=>'User Deleted',
            'User'=> $user->delete()
        ], 200);
    }
}
