<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class listeDesProduits extends Model
{
     protected $fillable = [
        "Montant",
        "Quantite",
        "TypeProduit",
        "LibelleProduit",
        "nEstUnServicePrive",
        "IdProduit",
        "Reference_code_Produit",
    ];
}
