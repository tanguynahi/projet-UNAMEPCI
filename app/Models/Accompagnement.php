<?php

namespace App\Models;

use App\Models\Mutualiste;
use App\Models\TypePaiement;
use App\Models\Administrateur;
use App\Models\DemandeAccompagnement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accompagnement extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }

    public function typePaiment(): BelongsTo
    {
        return $this->belongsTo(TypePaiement::class);
    }
    public function demandeAccompagnement(): BelongsTo
    {
        return $this->belongsTo(DemandeAccompagnement::class);
    }
}
