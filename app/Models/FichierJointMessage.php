<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FichierJointMessage extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
