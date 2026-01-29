<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreationAccesInscriptionRequest extends FormRequest
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
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed', // vérifie password_confirmation
                'regex:/[a-z]/',      // au moins une lettre minuscule
                'regex:/[A-Z]/',      // au moins une lettre majuscule
                'regex:/[0-9]/',      // au moins un chiffre
                'regex:/[@$!%*#?&]/', // au moins un caractère spécial
            ],

            'password_confirmation' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.regex' => 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password_confirmation.required' => 'Veuillez confirmer votre mot de passe.'
        ];
    }
}
