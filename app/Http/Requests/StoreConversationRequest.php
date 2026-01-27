<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
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

            'sujet' => 'required|string|min:3',
            'message' => 'required|string|min:3',
            'lien_document' => 'nullable|mimes:jpg,jpeg,png,pdf',
        ];
    }

    public function messages()
    {
        return [
            'sujet.required' => 'Le sujet du Message  est obligatoire.',
            'sujet.string' => 'Le sujet du message doit être une chaîne de caractères.',
            'sujet.min' => 'Le sujet du message  doit contenir au moins 3 caractères.',
            'message.required' => 'Le Message  est obligatoire.',
            'message.string' => 'Le  message doit être une chaîne de caractères.',
            'message.min' => 'Le message  doit contenir au moins 3 caractères.',
            'lien_document.required' => 'Le document n\'est  pas obligateoire',
            'lien_document.image' => "Veuillez choisir un fichier.",
            'lien_document.mimes' => "Format de fichier nom pris en charge, veuillez choisir un fichier (jpg,jpeg,png,pdf)",
        ];
    }
}
