<?php

namespace App\Models;

use App\Models\Mutualiste;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CodeValidation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function mutualiste(): BelongsTo
    {
        return $this->belongsTo(Mutualiste::class);
    }
}
