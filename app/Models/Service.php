<?php

namespace App\Models;

use App\Models\InteretService;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function interetServies()
    {
        return $this->hasMany(InteretService::class);
    }
}
