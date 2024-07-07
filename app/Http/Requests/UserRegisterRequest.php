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
            'name' => 'string|max:255',
            'age' => 'nullable|sometimes|integer|min:0|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|sometimes|string',
            'password' => 'nullable|sometimes|string|min:5',
            'sex' => 'nullable|sometimes|string',
            'weight' => 'nullable|sometimes|string',
            'ethnicity' => 'nullable|sometimes|string',
            'bodyType' => 'nullable|sometimes|string',
            'bodyGoal' => 'nullable|sometimes|string',
            'bloodPressure' => 'nullable|sometimes|string',
            'bloodSugar' => 'nullable|sometimes|string',
            'isGoogle' => 'nullable|sometimes|boolean',
            'isPremium' => 'nullable|sometimes|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ];
    }
}
