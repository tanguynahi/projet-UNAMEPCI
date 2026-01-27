<?php

namespace App\Models;

use App\Models\Direction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotisation extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function direction() : BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }
    
}
