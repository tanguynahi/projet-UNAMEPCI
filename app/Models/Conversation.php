<?php

namespace App\Models;

use App\Models\Message;
use App\Models\FichierJointMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    public function fichierJointMessages(): HasMany
    {
        return $this->hasMany(FichierJointMessage::class);
    }
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    // public static function Nonlu($messId)
    // {
    //     return Conversation::where('statut', '=', 2)
    //         ->where('recepteur', '=', 1)
    //         ->where('message_id', $messId)
    //         ->count();
    // }
}
