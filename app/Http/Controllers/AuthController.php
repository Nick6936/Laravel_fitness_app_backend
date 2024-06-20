<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;


class AuthController extends Controller
{
    public function register(UserRegisterRequest $request){
        $validatedData = $request->validated();// gets data from UserRegisterRequest in App\Http\Requests\UserRegisterRequest

        $user = User::create([
            'name' => $validatedData['name'],
            'age' => $validatedData['age'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => bcrypt($validatedData['password']),
            'sex' => $validatedData['sex'],
            'weight' => $validatedData['weight'],
            'ethnicity' => $validatedData['ethnicity'],
            'bodyType' => $validatedData['bodyType'],
            'bodyGoal' => $validatedData['bodyGoal'],
            'bloodPressure' => $validatedData['bloodPressure'],
            'bloodSugar' => $validatedData['bloodSugar'],
            'isPremium' => $validatedData['isPremium'] ?? 0, // Default to 0 if not provided
        ]);
        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }
    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}