<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'age' => 'required|integer|min:0|max:100',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required',
                    'password' => 'required',
                    'sex' => 'required',
                    'ethnicity' => 'required',
                    'bodyType' => 'required',
                    'bodyGoal' => 'required',
                    'bloodPressure' => 'required',
                    'bloodSugar' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'error' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'age' => $request->age,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password,
                'sex' => $request->sex,
                'ethnicity' => $request->ethnicity,
                'bodyType' => $request->bodyType,
                'bodyGoal' => $request->bodyGoal,
                'bloodPressure' => $request->bloodPressure,
                'bloodSugar' => $request->bloodSugar,
                'isPremium' => $request->has('isPremium') ? $request->isPremium: 0,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Validation Successful',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'Registered User' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
