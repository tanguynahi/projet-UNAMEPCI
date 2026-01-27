<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Mutualiste;
use App\Models\TypeCompte;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compte extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }

    public function typeCompte(): BelongsTo
    {
        return $this->belongsTo(TypeCompte::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compte) {
            $baseTimestamp = Carbon::now()->format('YmdHis');
            $baseNumero = intval($baseTimestamp); // Convertir en entier pour les opérations arithmétiques
            $numeroCompte = 'CPT-' . $baseTimestamp;
            $counter = 0;

            while (Compte::where('numero_compte', $numeroCompte)->exists()) {
                $counter++;
                $newNumero = $baseNumero + $counter; // Ajouter le compteur à la partie numérique
                $numeroCompte = 'CPT-' . $newNumero;
            }

            $compte->numero_compte = $numeroCompte;
        });
    }

}
