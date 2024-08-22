<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callbackGoogle()
    {
        try{
            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $google_user->getId())->first();

            if(!$user) {
                $user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'password' => bcrypt('defaultPassword'),
                ]);
            }
            // Login the user and generate a JWT token
            $token = auth('api')->login($user);

            return $this->respondWithToken($token);
             //else{
            //     Auth::login($user);

            //     // Generate a JWT token instead of redirecting
            //     $token = auth()->login($user);
            //     return response()->json(['token' => $token]);

            //     // return redirect()->intended('dashboard');


            // }

        } catch (\Exception $e) {
            Log::error('Error during Google OAuth callback: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to authenticate with Google'], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 10080 // Adjust TTL as needed
        ]);
    }
}
