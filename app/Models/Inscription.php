<?php

namespace App\Models;

use App\Models\Ville;
use App\Models\TypePiece;
use App\Models\Specialite;
use App\Models\Administrateur;
use App\Models\FormeJuridique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $guarded = [];

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }
    public function typePiece(): BelongsTo
    {
        return $this->belongsTo(TypePiece::class);
    }

    public function formeJuridique(): BelongsTo
    {
        return $this->belongsTo(FormeJuridique::class);
    }
    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite::class);
    }
    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }
}
