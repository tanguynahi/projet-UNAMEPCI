<?php

namespace App\Models;

use App\Models\PaiementInitiale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentPaiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'lien_photo',
        'facturation_id', // Ajoutez ceci
        'paiement_initiale_id', // Ajoutez ceci
        'pret_id'
    ];

    public function paiementInitiale(): BelongsTo
    {
        return $this->belongsTo(PaiementInitiale::class);
    }
    // public function facturation(): BelongsTo
    // {
    //     return $this->belongsTo(Facturation::class);
    // }
}
