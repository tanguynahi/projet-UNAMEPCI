<?php

namespace App\Imports;

use App\Models\Mutualiste;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MutualistesImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     *
     * @return Collection
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }

    /**
     * Règles de validation pour chaque ligne du fichier Excel
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'numero_matricule' => 'nullable|string|max:255',
            'noms'             => 'required|string|max:255',
            'prenoms'          => 'required|string|max:255',
            'contact'          => 'required|string|min:10|max:10',
            'email'            => 'nullable|email|max:255',
        ];
    }

    /**
     * Messages de validation personnalisés
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'numero_matricule.string' => 'Le numéro de matricule doit être une chaîne de caractères.',
            'numero_matricule.max'    => 'Le numéro de matricule ne doit pas dépasser 255 caractères.',

            'noms.required' => 'Le nom est obligatoire.',
            'noms.string'   => 'Le nom doit être une chaîne de caractères.',
            'noms.max'      => 'Le nom ne doit pas dépasser 255 caractères.',

            'prenoms.required' => 'Le prénom est obligatoire.',
            'prenoms.string'   => 'Le prénom doit être une chaîne de caractères.',
            'prenoms.max'      => 'Le prénom ne doit pas dépasser 255 caractères.',

            'contact.required' => 'Le contact est obligatoire.',
            'contact.string'   => 'Le contact doit être une chaîne de caractères.',
            'contact.min'      => 'Le contact doit contenir au moins 10 chiffres.',
            'contact.max'      => 'Le contact ne doit pas dépasser 10 chiffres.',

            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.max'   => 'L\'email ne doit pas dépasser 255 caractères.',
        ];
    }

    /**
     * Taille du batch pour les inserts
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Taille du chunk pour la lecture
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 10000;
    }
}
