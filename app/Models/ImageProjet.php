<?php

namespace App\Models;

use App\Models\Projet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageProjet extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }
}
