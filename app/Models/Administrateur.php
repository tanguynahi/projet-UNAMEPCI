<?php

namespace App\Models;

use App\Models\User;
use App\Models\Slide;
use App\Models\Ville;
use App\Models\Parametre;
use App\Models\Conversation;
use App\Models\DroitAdhesion;
use App\Models\InteretService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrateur extends Model
{
    use HasFactory,SoftDeletes, Notifiable;
    protected $guarded = [];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ville() : BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    public function droitAdhesion(): HasMany
    {
        return $this->hasMany(DroitAdhesion::class);
    }

    public function imagesSlide(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function parametres(): HasOne
    {
        return $this->hasOne(Parametre::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
    public function interetServies()
    {
        return $this->hasMany(InteretService::class);
    }
}
