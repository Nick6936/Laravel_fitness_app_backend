<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::get();

        return response()-> json([
            'message' => 'All Purchases',
            'Statement' => $payments
        ], 200);
         
    

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'payment' => 'sometimes|decimal',
                'medium' => 'sometimes|string',
                'item' => 'sometimes|string',
            ]);

            $user = User::findOrFail($validatedData['user_id']);

            $payment = Payment::create([
                'user_id' => $validatedData['user_id'],
                'name' => $user->name,
                'amount' => $validatedData['amount'] ?? 1000,
                'medium' => $validatedData['medium'] ?? "esewa",
                'item' => $validatedData['item'] ?? "Fitness App Premium",
            ]);

            //update the user to premium user
            $user->isPremium = 1;
            $user->save();

            return response()->json([
                'message' => 'New Payment Added',
                'Payment' => $payment
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error adding payment: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to add new payment',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $payment = Payment::where('user_id', $user_id)->get();

        return response()->json([
            'message' => 'Your Payment Statement',
            'Statement' => $payment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
