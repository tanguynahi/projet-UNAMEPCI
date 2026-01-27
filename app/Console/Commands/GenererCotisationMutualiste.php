<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Facturation;
use Illuminate\Console\Command;
use App\Models\CotisationMutualiste;
use App\Http\Controllers\FacturationController;

class GenererCotisationMutualiste extends Command
{
    protected $signature = 'cotisation:generer';
    protected $description = 'Générer automatiquement les cotisations journalières pour les mutualistes';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        // Obtenir tous les mutualistes avec fréquence de paiement journalière
        $mutualistes = CotisationMutualiste::where('frequence_paiement', 'Journalière')
            ->whereIn('status', [1, 2])
            ->whereDate('created_at', Carbon::yesterday()) // Compare uniquement la date
            ->where('date_fin', '>=', Carbon::now()->format('Y-m-d'))
            ->get();
        foreach ($mutualistes as $mutualiste) {
            CotisationMutualiste::create([
                'cotisation_id' => $mutualiste->cotisation_id,
                'type_paiement_id' => $mutualiste->type_paiement_id,
                'mutualiste_id' => $mutualiste->mutualiste_id,
                'montant' => $mutualiste->montant,
                'frequence_paiement' => $mutualiste->frequence_paiement,
                'status' => 2, // Statut en attente pour le lendemain
                'date_debut' => $mutualiste->date_debut,
                'date_fin' => $mutualiste->date_fin,
            ]);
            // Mettre à jour le status à "4" pour la cotisation courante deja solde
            if ($mutualiste->status == 1) {
                $mutualiste->update([
                    'status' => 4,
                ]);
            }
        }
        // traitement selon les projets (faturation journaliere)
        $cotisations = CotisationMutualiste::where('date_fin', '<', Carbon::now()->format('Y-m-d'))
            ->get();
        foreach ($cotisations as $cotisation) {
            // statut 5 veux dit cotisation terminer
            $cotisation->update([
                'status' => 5,
            ]);
        }
        // pour les facturations
        $facturations = Facturation::where('periode_id', 2)
            ->whereIn('status', [1, 3]) // 1 impayer , 3 payer , 2 suprpimer  , 4 solder terminer
            ->whereDate('created_at', Carbon::yesterday()) // Compare uniquement la date
            ->where('date_fin', '>=', Carbon::now()->format('Y-m-d'))
            ->get();
        foreach($facturations as $facturation)
        {
            Facturation::create([
                'mutualiste_id' => $facturation->mutualiste_id,
                'projet_mutualiste_id' => $facturation->projet_mutualiste_id,
                'redevance_id' => $facturation->redevance_id,
                'periode_id' => $facturation->periode_id,
                'total_apayer' => $facturation->total_apayer,
                'montant_periodique' => $facturation->montant_periodique,
                'frequence' => $facturation->frequence,
                'total_payer' => $facturation->total_payer,
                'reste_apayer' => $facturation->reste_apayer,
                'date_debut' => $facturation->date_debut,
                'date_fin' => $facturation->date_fin,
                'date_facturation' => $facturation->date_facturation,
                'date_prochain_paiement' => $facturation->date_prochain_paiement,
                'status' => 1,
            ]);
        }
        // fin de facturations
        $terminerFat = Facturation::where('date_fin', '<', Carbon::now()->format('Y-m-d'))
        ->get();
        foreach($terminerFat as $terminer)
        {
            $terminer->update([
                'status' => 4,
            ]);
        }

        $this->info('Cotisations journalières générées avec succès.');
    }
}
