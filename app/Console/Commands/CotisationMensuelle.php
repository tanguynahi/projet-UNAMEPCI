<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Facturation;
use Illuminate\Console\Command;
use App\Models\CotisationMutualiste;

class CotisationMensuelle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cotisation:mensuelle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generer automatiquement une cotisation mensuelles pour les mutualistes';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        // Obtenir tous les mutualistes avec fréquence de paiement hebdomadaire
        $mutualistes = CotisationMutualiste::where('frequence_paiement', 'Mensuelle')
            ->whereIn('status', [1, 2])
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->where('date_fin', '>=', Carbon::today())
            ->get();
        foreach ($mutualistes as $mutualiste) {
            // Créer une nouvelle cotisation pour le lendemain
            CotisationMutualiste::create([
                // 'administrateur_id' => $mutualiste->administrateur_id,
                'cotisation_id' => $mutualiste->cotisation_id,
                'type_paiement_id' => $mutualiste->type_paiement_id,
                'mutualiste_id' => $mutualiste->mutualiste_id, // adapter selon tes colonnes
                'montant' => $mutualiste->montant,
                'frequence_paiement' => $mutualiste->frequence_paiement,
                'status' => 2, // Statut en attente pour le lendemain
                'date_debut' => $mutualiste->date_debut,
                'date_fin' => $mutualiste->date_fin,
            ]);
            // Mettre à jour le statut à "4" pour la cotisation courante deja solde
            if ($mutualiste->status == 1) {
                $mutualiste->update([
                    'status' => 4,
                ]);
            }
        }
        // les id des periodes : 1 immediat, 2 journaliere, 3 hebdomadaire ,4 mensuelle, 5 Annuelle , 6 Aperiodique
        $facturations = Facturation::where('periode_id', 4)
            ->whereIn('status', [1, 3])
            ->where('date_fin', '>=', Carbon::today())
            // ->where('date_fin', '>=', Carbon::now()->format('Y-m-d'))
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->get();
        foreach ($facturations as $facturation) {
            Facturation::create([
                // 'administrateur_id' => $facturation->administrateur_id,
                'mutualiste_id' => $facturation->mutualiste_id,
                'projet_mutualiste_id' => $facturation->projet_mutualiste_id,
                'produit_projet_id' => $facturation->produit_projet_id,
                'redevance_id' => $facturation->redevance_id,
                'periode_id' => $facturation->periode_id,
                'total_apayer' => $facturation->total_apayer,
                'montant_periodique' => $facturation->montant_periodique,
                'frequence' => $facturation->frequence,
                'total_payer' => $facturation->total_payer,
                'reste_apayer' => $facturation->reste_apayer,
                'date_debut' => $facturation->date_debut,
                'date_fin' => $facturation->date_fin,
                'date_facturation' => Carbon::now()->format('Y-m-d'),
                'status' => 1, // statut en attente
            ]);

            // si son statut etait a 3 qui est solde
            if ($facturation->status == 3) {
                $facturation->update([
                    'status' => 4, // il est masque sur la plateforme du mutualiste
                ]);
            }
        }
        $this->info('Cotisations Mensuelle générées avec succès.');
    }
}
