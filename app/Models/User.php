<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Message;
use App\Models\Mutualiste;
use App\Models\Administrateur;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function administrateur(): HasOne
    {
        return $this->hasOne(Administrateur::class);
    }

    public function mutualiste(): HasOne
    {
        return $this->hasOne(Mutualiste::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    // Si l'utilisateur est client, accéder aux conversations via le modèle Client
    public function mutualisteConversations()
    {
        return $this->mutualiste ? $this->mutualiste->conversations() : null;
    }

    // Si l'utilisateur est administrateur, accéder aux conversations via le modèle Administrateur
    public function administrateurConversations()
    {
        return $this->administrateur ? $this->administrateur->conversations() : null;
    }
}
