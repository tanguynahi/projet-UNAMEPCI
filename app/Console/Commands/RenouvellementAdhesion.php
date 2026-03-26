<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Taxe;
use App\Models\CarteMembre;
use App\Models\DroitAdhesion;
use Illuminate\Console\Command;

class RenouvellementAdhesion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renouvellement-adhesion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //*
        $today = Carbon::today();
        $listesCartes = CarteMembre::whereDate('date_expiration', '<=', $today)
            ->where('genere', 1)
            ->where('status', 1)
            ->get();
        $taxeAdhesion =  Taxe::findOrFail(1); // montant droit adhesion
        foreach ($listesCartes as $carte) {
            // la carte expire
            $carte->status = 2;
            $carte->genere = 3;
            $carte->save();
            // renouvellment de droit d'adhesion
            $droitAdhesion = DroitAdhesion::where('mutualiste_id', $carte->mutualiste_id)->first();
            $droitAdhesion->montant = $taxeAdhesion->montant ?? 10000;
            $droitAdhesion->status = 2;
            $droitAdhesion->save();
        }
    }
}
