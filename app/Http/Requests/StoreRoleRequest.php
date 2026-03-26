<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du rôle est obligatoire.',
            'name.string' => 'Le nom du rôle doit être une chaîne de caractères.',
            'name.max' => 'Le nom du rôle ne doit pas dépasser 50 caractères.',
            'name.unique' => 'Ce nom de rôle existe déjà.',

            'permissions.required' => 'Vous devez sélectionner au moins une permission.',
            'permissions.array' => 'Les permissions doivent être envoyées sous forme de tableau.',
            'permissions.min' => 'Vous devez sélectionner au moins une permission.',
            'permissions.*.exists' => 'La permission sélectionnée est invalide.',
        ];
    }
}
