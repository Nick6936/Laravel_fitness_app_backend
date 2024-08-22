<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
{
    try {
        $validatedData = $request->validated();

        // Handle photo upload
        $photoName = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/user-photos');
            $photoName = basename($photoPath);
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'age' => $validatedData['age'] ?? null,
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null,
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : bcrypt('defaultPassword'),
            'sex' => $validatedData['sex'] ?? null,
            'weight' => $validatedData['weight'] ?? null,
            'ethnicity' => $validatedData['ethnicity'] ?? null,
            'bodyType' => $validatedData['bodyType'] ?? null,
            'bodyGoal' => $validatedData['bodyGoal'] ?? null,
            'bloodPressure' => $validatedData['bloodPressure'] ?? null,
            'bloodSugar' => $validatedData['bloodSugar'] ?? null,
            'google_id' => $validatedData['google_id'] ?? null,
            'isPremium' => $validatedData['isPremium'] ?? 0,
            'photo_name' => $photoName,
        ]);

        $token = auth('api')->login($user);
        return $this->respondWithToken($token);

    } catch (\Exception $e) {
        // Log the exception for debugging if needed
        Log::error('Error registering user: ' . $e->getMessage());

        return response()->json([
            'error' => 'Failed to register user',
            'message' => $e->getMessage()
        ], 500);
    }
}

    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Incorrect Credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    
    public function me()
    {
        $user = auth('api')->user();
        $everydayController = new EverydayController();
        $totals = $everydayController->getUserTotals($user->user_id);

        return response()->json([
            'user' => $user,
            'totals' => $totals
        ]);
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
            'expires_in' => auth('api')->factory()->getTTL() * 10080
        ]);
    }

    public function update(Request $request)
    {
        $user = auth('api')->user();

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:100',
            'email' => 'nullable|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:5',
            'sex' => 'nullable|string',
            'weight' => 'nullable|string',
            'ethnicity' => 'nullable|string',
            'bodyType' => 'nullable|string',
            'bodyGoal' => 'nullable|string',
            'bloodPressure' => 'nullable|string',
            'bloodSugar' => 'nullable|string',
            'google_id' => 'nullable|string',
            'isPremium' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $user->update(array_merge($user->only([
            'name', 'age', 'email', 'phone', 'sex', 'weight', 'ethnicity', 'bodyType', 'bodyGoal', 'bloodPressure', 'bloodSugar', 'google_id','isPremium'
        ]), $validatedData, [
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : $user->password,
        ]));

        // Handle photo update
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo_name) {
            Storage::delete('public/user-photos/' . $user->photo_name);
        }

        // Store new photo
        $photoPath = $request->file('photo')->store('public/user-photos');
        $user->photo_name = basename($photoPath);
        $user->save();
    }

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function deleteAccount()
    {
        $user = auth('api')->user();

        // Delete user's photo from storage if it exists
    if ($user->photo_name) {
        Storage::delete('public/user-photos/' . $user->photo_name);
    }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }



    
}