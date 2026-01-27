<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutualisteLoginRequest extends FormRequest
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            //
            "email" => "required|email|exists:mutualistes,email",
            "password"=> "required|string|min:6",
        ];
    }

    public function messages()
    {
        return [
            'email.required'=> "L'adresse email est requis.",
            'email.email'=> "Entrer une adresse email valide.",
            'email.exists'=> "Aucun compte associé à cette adresse e-mail.",
            'password.required'=> "Le mot de passe est requis",
            'password.min'=> "Le mot de passe doit comporter au moins 6 caractères.",
        ];
    }
}
