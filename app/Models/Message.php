<?php

namespace App\Models;

use App\Models\Mutualiste;
use App\Models\Conversation;
use App\Models\FichierJointMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $fillable = ['recepteur'];
    public function fichierJointMessages(): HasMany
    {
        return $this->hasMany(FichierJointMessage::class);
    }
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }
}

// public function direction(): BelongsTo
//     {
//         return $this->belongsTo(Direction::class);
//     }


