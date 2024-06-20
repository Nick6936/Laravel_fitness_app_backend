<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|string|min:5',
            'sex' => 'required',
            'weight' => 'required',
            'ethnicity' => 'required',
            'bodyType' => 'required',
            'bodyGoal' => 'required',
            'bloodPressure' => 'required',
            'bloodSugar' => 'required',
            'isPremium' => 'sometimes|boolean',
        ];
    }
}
