<?php

namespace App\Models;

use App\Models\User;
use App\Models\Corps;
use App\Models\Grade;
use App\Models\Ville;
use App\Models\Compte;
use App\Models\Projet;
use App\Models\Paiement;
use App\Models\TypePiece;
use App\Models\Specialite;
use App\Models\CarteMembre;
use App\Models\Conversation;
use App\Models\DemandeProjet;
use App\Models\DroitAdhesion;
use App\Models\Accompagnement;
use App\Models\CodeValidation;
use App\Models\DemandeProduit;
use App\Models\FormeJuridique;
use App\Models\PaiementInitiale;
use App\Models\CotisationMutualiste;
use App\Models\DemandeAccompagnement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mutualiste extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }
    public function villePersonnel(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    public function corp(): BelongsTo
    {
        return $this->belongsTo(Corps::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function typePiece(): BelongsTo
    {
        return $this->belongsTo(TypePiece::class);
    }
    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite::class);
    }
    public function formeJuridique(): BelongsTo
    {
        return $this->belongsTo(FormeJuridique::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function paiementInitials(): HasMany
    {
        return $this->hasMany(PaiementInitiale::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }

    public function cotisations(): HasMany
    {
        return $this->hasMany(CotisationMutualiste::class);
    }

    public function accompagnements(): HasMany
    {
        return $this->hasMany(Accompagnement::class);
    }

    public function droitAdhesion(): HasOne
    {
        return $this->hasOne(DroitAdhesion::class);
    }
    public function carteMembre(): HasOne
    {
        return $this->hasOne(CarteMembre::class);
    }

    public function codeValidation(): HasOne
    {
        return $this->hasOne(CodeValidation::class);
    }

    public function compte(): HasOne
    {
        return $this->hasOne(Compte::class);
    }


    public function demandeProduits(): HasMany
    {
        return $this->hasMany(DemandeProduit::class);
    }

    public function demandeAccompagnements(): HasMany
    {
        return $this->hasMany(DemandeAccompagnement::class);
    }
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
