<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Administrateur;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InteretService extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];



    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
