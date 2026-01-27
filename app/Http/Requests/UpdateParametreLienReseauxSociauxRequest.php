<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParametreLienReseauxSociauxRequest extends FormRequest
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
            'lien_facebook' => 'nullable|url',
            'lien_twitter' => 'nullable|url',
            'lien_instagram' => 'nullable|url',
            'lien_linkedin' => 'nullable|url',
            'lien_youtube' => 'nullable|url',
            'lien_whatsapp' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'lien_facebook.url' => 'Le lien du compte facebook n\'est pas une URL valide.',
            'lien_twitter.url' => 'Le lien du compte twitter n\'est pas une URL valide.',
            'lien_instagram.url' => 'Le lien du compte instagram n\'est pas une URL valide.',
            'lien_linkedin.url' => 'Le lien du compte linkedin n\'est pas une URL valide.',
            'lien_youtube.url' => 'Le lien du compte youtube n\'est pas une URL valide.',
            'lien_whatsapp.url' => 'Le lien du compte whatsapp n\'est pas une URL valide.',
        ];
    }
}
