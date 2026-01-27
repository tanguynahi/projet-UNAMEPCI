<?php

use App\Models\CotisationMutualiste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CorpsController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\RedevanceController;
use App\Http\Controllers\TypePieceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\MutualisteController;
use App\Http\Controllers\TypeCompteController;
use App\Http\Controllers\FacturationController;
use App\Http\Controllers\ImageProjetController;
use App\http\Controllers\Chat\MessageController;
use App\Http\Controllers\TypeDocumentController;
use App\Http\Controllers\TypePaiementController;
use App\Http\Controllers\DroitAdhesionController;
use App\Http\Controllers\ProduitProjetController;
use App\Http\Controllers\AccompagnementController;
use App\Http\Controllers\AdministrateurController;
use App\Http\Controllers\DemandeProduitController;
use App\Http\Controllers\InteretServiceController;
use App\Http\Controllers\DocumentProduitController;
use App\Http\Controllers\DocumentPaiementController;
use App\Http\Controllers\Home\TableaubordController;
use App\Http\Controllers\ProjetMutualisteController;
use App\http\Controllers\Chat\ConversationController;
use App\Http\Controllers\Trier\TrieMessageController;
use App\Http\Controllers\ConnexionMutualisteController;
use App\Http\Controllers\CotisationMutualisteController;
use App\Http\Controllers\DemandeAccompagnementController;
use App\Http\Controllers\ConnexionAdministrateurController;
use App\Http\Controllers\Home\NotificationMutualisteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('accueil');
    Route::get('/presentations', 'presentation')->name('presentation');
    Route::get('/inscription-mutualiste', 'inscription');
    Route::get('/projet', 'projets')->name('projet');
    Route::get('/detail-projet-accueil/{projet}', 'detail_projet')->name('detail.projet');
    Route::get('/actualites', 'actualite')->name('actualites');
    Route::get('/actualite-detail/{actualite}', 'detailsActualites')->name('detail.actualites');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/connexion', 'connexionMutualiste')->name('connexion');
    Route::get('/detail-des-produits/{produitprojet}', 'detailProduitMutualiste')->name('mutualiste.detailproduit');
    Route::post('/Contact-traiter', 'traitementContact')->name('mutualistes.contacter');
    Route::get('/recu-automatique/{id}', 'generatePDF')->name('telecharger.recu');
});
Route::controller(ConnexionMutualisteController::class)->group(function () {
Route::post('/connexion-mutualiste', 'connexionMutualiste')->name('connexion.mutualiste');});
Route::post('/connexion-administrateur', [ConnexionAdministrateurController::class, 'connexionAdministrateur'])->name('connexion.administrateur');
Route::post('/logout-user', [LoginController::class, 'logoutUser'])->name('logout.user');
Route::controller(MutualisteController::class)->group(function () {
    Route::get('/validation-inscription/{code}', 'validationInscriptionMutualite')->name('validation.inscription');
    Route::put('/finaliser-inscription/{mutualiste}', 'finaliserInscriptionMutualiste')->name('finaliser.inscription');
})->middleware('verifierRoleUtilisateur:mutualiste');


Route::controller(InteretServiceController::class)->group(function(){
    Route::get('/recuperation-infoInteret/{serviceId}/{montant}','recuperation')->name('recup.interet');
});

Route::middleware('auth')->group(function () {

    Route::middleware('verifierRoleUtilisateur:mutualiste')->group(function () {
        // route du tableau de bord du mutualiste
        Route::prefix('mutualiste')->group(function () {
            Route::controller(TableaubordController::class)->group(function () {
                Route::get('/tableau_de_bord', 'index')->name('espace.accueil');
                Route::get('/profil', 'profil')->name('espace.profil')->middleware('verifier.droitAdhesion');
                Route::get('/projets_espace', 'projet')->name('espace.projet')->middleware('verifier.droitAdhesion');
                Route::get('/contribution', 'cotisation')->name('espace.cotisation')->middleware('verifier.droitAdhesion');
                Route::get('/historique-page', 'historique_index')->name('espace.historique_index')->middleware('verifier.droitAdhesion');
                // route de generation de recu automatique

                // teste pour produits
                Route::get('/detail-produit', 'produitIndex')->middleware('verifier.droitAdhesion');
                // Route::get('', 'liste_projet_mutualiste')->name('');
                Route::get('/chat-mutualiste', 'messageMutualiste')->name('message.mutualiste')->middleware('verifier.droitAdhesion');
                Route::get('/chat-message/{administrateur}', 'voirMessage')->name('voir.message')->middleware('verifier.droitAdhesion');
                Route::get('/resultat-Paiement/{codePaiement}','resultatPaiement')->name('resultat.paiement');
                Route::get('/boutiqueMonetaire','boutiques')->name('boutique.index');


            });
            // route des conversations  concernant la chat ( controller message)
            Route::controller(ConversationController::class)->group(function () {
                Route::get('/boite-de-reception', 'indexMutualiste')->name('mutualiste.boiteReception')->middleware('verifier.droitAdhesion');
                Route::get('/nouvelle-discultion', 'createMutualiste')->name('mutualiste.nouvelleDiscution')->middleware('verifier.droitAdhesion'); // lorsque c'est nouvelle discultion o,n ne passa pas id
                Route::post('/traitement-message/{id}', 'storeMutualiste')->name('mutualiste.traitementMessage')->middleware('verifier.droitAdhesion');
                Route::get('/message/{id}', 'conversationMutualiste')->name('mutualiste.message')->middleware('verifier.droitAdhesion'); // lorsque il voir le message a accede a l'interface de discultion
                Route::post('/traitement-discultion/{id}', 'discultionMutualiste')->name('message.discultion')->middleware('verifier.droitAdhesion');
            });


            Route::controller(DemandeAccompagnementController::class)->group(function () {
                Route::get('/liste-accompagnement', 'listeDemandeAccompagnement')->name('liste.demandeaccompagnement')->middleware('verifier.droitAdhesion');
                Route::get('/accompagnement-en-attente/{demandeAccompagnement}', 'accompagnementAttente')->name('accompagnement.attente')->middleware('verifier.droitAdhesion');
                Route::get('/modifier-demandeAccompagnement/{id}','editDemandeMutualiste')->name('demandeaccompagnement.modifier');
                Route::put('/demandeaccompagnement-modifier/{id}','miseAJourDemande')->name('modification.demandeaccompagnement');
                Route::get('/detail-demandeaccompagnement/{id}','detaildemandeAccompagnement')->name('detail.demandeaccompagnement');

                Route::get('/pageErrorService','pageErrorpret')->name('error.pret');
                Route::post('paiementHubRemboursement-dette','remboursHub')->name('detteRembou.hub');
                Route::post('/enregisterDocumentPaiement-pret','rembourDetteEnregiste')->name('enregis.pret');
                // les images
                Route::get('/imagesDesPaiements-Pret/{id}','galeriImagePret')->name('imagesPaie.pret');

            });

            Route::controller(CotisationMutualisteController::class)->group(function () {
                Route::get('/liste-cotisation', 'listeCotisationMutualiste')->name('Cotisation.mutualiste')->middleware('verifier.droitAdhesion');
                Route::get('/resumeDesCotisations/{id}', 'detailCotisaMutualiste')->name('resume.cotisationMutual')->middleware('verifier.droitAdhesion');
                Route::get('/traitementHubCotisation/{id}','paiementCotisations')->name('paiementCotisation.mutualiste')->middleware('verifier.droitAdhesion');
            });
            Route::controller(MutualisteController::class)->group(function () {
                // modification d'un mutualiste
                Route::put('/mutualiste-modification/{id}', 'modificationMutualiste')->name('mutualistes.modification')->middleware('verifier.droitAdhesion');
                // modifier le mot de passe d'un mutualiste
                Route::post('/change-password', 'changePasswordMutualiste')->name('mutualistes.changepassword')->middleware('verifier.droitAdhesion');
            });

            Route::controller(PaiementController::class)->group(function () {
                Route::get('/paiement-adhesion', 'paiementAdhesion')->name('paiement.adhesion');// paiement droit d'adhesion

                Route::get('/paiement-produit/{id}', 'paiementProduitFacturation')->name('paiement.produit'); // paiement de produit

                // Route::get('')
            });
            Route::controller(NotificationMutualisteController::class)->group(function () {
                Route::get('/notifications-mutualiste', 'notificationMutualiste')->name('mutualiste.notification')->middleware('verifier.droitAdhesion');
            });


            // listes des produts aquis selon un mutualiste connecter
            Route::controller(ProjetMutualisteController::class)->group(function () {
                Route::get('/affaire-detail/{projetMutualiste}', 'detailMutualisteProduitAcquis')->name('produitacquis.detail')->middleware('verifier.droitAdhesion');
                Route::get('/paiements-listeaffaire/{projetMutualiste}', 'paiementProduitAcquis')->name('produitacquis.paiement')->middleware('verifier.droitAdhesion');
                Route::get('/listeProjetAcquis','listesProdAcquis')->name('listeProd.acquisMu')->middleware('verifier.droitAdhesion'); // liste des projet acquis mutualiste

                Route::get('/detailProduitAcquis/{id}','detailProjet')->name('prodAcquis.mutuID')->middleware('verifier.droitAdhesion');
                Route::get('/espace-client/ficherPaiement','fichePaiement')->name('fichePaiement.mutualiste');

                Route::post('/passeHubPaiProd','hubPaiemPro')->name('passeHub.paiement');
                Route::get('/documents-paiement/{id}','viewGalleriPaiemnt')->name('docs.paiement');
                Route::get('/paiement-detail/{id}','showPaiement')->name('paieLign.show');
            });

            Route::controller(DemandeProduitController::class)->group(function () {
                Route::get('/liste-demande-produit', 'listeDemande')->name('liste.demandeproduit')->middleware('verifier.droitAdhesion');
                Route::post('/formulaire-demande-produit', 'enregistrerDemande')->name('demande.produit')->middleware('verifier.droitAdhesion');
                Route::get('/demande-produit', 'creation')->middleware('verifier.droitAdhesion');
            });


            Route::controller(DocumentPaiementController::class)->group(function(){
                Route::post('/infosPaiementProd','remplissageInfoPaie')->name('remp.infoPaiem');// infos de paiment d'un article a soumet cher admin
            });



        });
    });


    // les routes administrateurs
    Route::middleware('verifierRoleUtilisateur:super-administrateur,administrateur')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/dashboard-statistiques', 'statistiques')->name('dashboard.stats');
            Route::get('/paiements-projets', 'PaiementsProjets')->name('paiements.projets');
            Route::get('/paiements-cotisations', 'PaiementsProjets')->name('paiements.cotisations');
            Route::get('/paiements-accompagnements', 'PaiementsProjets')->name('paiements.accompagnements');
            Route::get('/paiements-droits-adhesion', 'PaiementsDroitAdhesion')->name('paiements.droits.adhesion');

            Route::get('/paiement-caisseFPM','caissePaiement')->name('paiements.caisse');
            Route::get('/galeriesPaiement-Especes/{id}','documentPaiement')->name('galerie.docPaiement');
            Route::get('/detailPaiement-cash/{id}','detailPaiementCaisse')->name('detail.paiementcash');
            Route::post('/accepterPaiementCash/{id}','accepterPaiement')->name('accepter.paiementcash');
            Route::post('/refuserPaiementCash/{id}','refuserPaiement')->name('refuser.paiementcash');
        });
    });
    Route::controller(MessageController::class)->group(function () {
        Route::post('/traitementAdministrateurEt/{id}', 'storeV')->name('message.administrateur');
        // premier Message a un mutualiste
        Route::get('/backoffice/nouveau-message', 'createMessageMutualiste')->name('message.nouveauMutualiste');
        Route::post('/MessageAMutualisteTraite/{id}', 'traitementMessageMutualiste')->name('message.administrateur_mutualiste');
        // premier Message a un particulier par email
        Route::get('/back/messageParticulier', 'createMessageAutre')->name('message.nouveauAutre');
        Route::post('/MessageAParticulierTraite/{id}', 'traitementMessageParticulier')->name('message.administrateur_particulier');
        Route::get('/conversationMail/{id}', 'editeParticulier')->name('message.editeparticulier');
        Route::post('/traitement-mail/{id}', 'discultionParMail')->name('discultion.emailParticulier');
    });
    // recherche sur un mutualiste
    // Route::get('/recherche-message',[TrieMessageController::class, 'index'])->name('recherche.message');

    Route::resources([
        'mutualistes' => MutualisteController::class,
        'administrateurs' => AdministrateurController::class,
        'villes' => VilleController::class,
        'corps' => CorpsController::class,
        'grades' => GradeController::class,
        'typepieces' => TypePieceController::class,
        'typepaiements' => TypePaiementController::class,
        'typecomptes' => TypeCompteController::class,
        'projets' => ProjetController::class,
        'imageprojets' => ImageProjetController::class,
        'cotisations' => CotisationController::class,
        'actualite' => ActualiteController::class,
        'directions' => DirectionController::class,
        'accompagnements' => AccompagnementController::class,
        'cotisationmutualistes' => CotisationMutualisteController::class,
        'projetmutualistes' => ProjetMutualisteController::class,
        'droitadhesions' => DroitAdhesionController::class,
        'slides' => SlideController::class,
        'comptes' => CompteController::class,
        'parametres' => ParametreController::class,
        'paiements' => PaiementController::class,
        'services' => ServiceController::class,
        'demandeproduits' => DemandeProduitController::class,
        'demandeaccompagnements' => DemandeAccompagnementController::class,
        'produitprojets' => ProduitProjetController::class,
        'typedocuments' => TypeDocumentController::class,
        'documentproduits' => DocumentProduitController::class,
        'periodes' => PeriodeController::class,
        'redevances' => RedevanceController::class,
        'facturations' => FacturationController::class,
        'equipes' => EquipeController::class,

        'messages' => MessageController::class,
        'interetservices' => InteretServiceController::class,
    ]);

    Route::middleware('verifierRoleUtilisateur:super-administrateur,administrateur')->group(function () {
        Route::put('/parametre-update-infos/{parametre}', [ParametreController::class, 'updateParametreInfosSite'])->name('parametre.update');
        Route::put('/parametre-update-reseaux/{parametre}', [ParametreController::class, 'updateParametreLienReseauxSociaux'])->name('parametre.update.lien.reseaux');
        Route::put('/mutualiste-restaure/{mutualiste}', [MutualisteController::class, 'restaureMutualiste'])->name('mutualiste.restaure');
        Route::put('/administrateur-restaure/{administrateur}', [AdministrateurController::class, 'restaureAdministrateur'])->name('administrateur.restaure');
        Route::put('/ville-restaure/{ville}', [VilleController::class, 'restaureVille'])->name('ville.restaure');
        Route::put('/corps-restaure/{corps}', [CorpsController::class, 'restaureCorps'])->name('corps.restaure');
        Route::put('/grade-restaure/{grade}', [GradeController::class, 'restaureGrade'])->name('grade.restaure');
        Route::put('/typepiece-restaure/{typePiece}', [TypePieceController::class, 'restaureTypePiece'])->name('typepiece.restaure');
        Route::put('/typepaiement-restaure/{typepaiement}', [TypePaiementController::class, 'restaureTypePaiement'])->name('typepaiement.restaure');
        Route::put('/typedocument-restaure/{typedocument}', [TypeDocumentController::class, 'restaureTypeDocument'])->name('typedocument.restaure');
        Route::put('/typecompte-restaure/{typeCompte}', [TypeCompteController::class, 'restaureTypeCompte'])->name('typecompte.restaure');
        Route::put('/projet-restaure/{projet}', [ProjetController::class, 'restaureProjet'])->name('projet.restaure');
        Route::put('/projet-mutualiste-restaure/{projetmutualiste}', [ProjetMutualisteController::class, 'restaureProjetMutualiste'])->name('projetmutualiste.restaure');
        Route::put('/produit-projet-restaure/{produitprojet}', [ProduitProjetController::class, 'restaureProduitProjet'])->name('produitprojet.restaure');
        Route::put('/document-produit-restaure/{documentproduit}', [DocumentProduitController::class, 'restaureDocumentProduit'])->name('documentproduit.restaure');

        Route::put('/imagesprojet-restaure/{imagesprojet}', [ImageProjetController::class, 'restaureImageProjet'])->name('imagesprojet.restaure');
        Route::put('/cotisation-restaure/{cotisation}', [CotisationController::class, 'restaureCotisation'])->name('cotisation.restaure');
        Route::put('/actualite-restaure/{actualite}', [ActualiteController::class, 'restaureActualite'])->name('actualite.restaure');
        Route::put('/direction-restaure/{direction}', [DirectionController::class, 'restaureDirection'])->name('direction.restaure');
        Route::put('/compte-restaure/{compte}', [CompteController::class, 'restaureCompte'])->name('compte.restaure');
        Route::put('/slide-restaure/{slide}', [SlideController::class, 'restaureSlide'])->name('slide.restaure');
        Route::put('/equipe-restaure/{equipe}', [EquipeController::class, 'restaureEquipe'])->name('equipe.restaure');
        Route::put('/redevance-restaure/{redevance}', [RedevanceController::class, 'restaureRedevance'])->name('redevance.restaure');
        Route::put('/facturation-restaure/{facturation}', [FacturationController::class, 'restaureFacturation'])->name('facturation.restaure');
        Route::put('/slide-restaure/{slide}', [SlideController::class, 'restaureSlide'])->name('slide.restaure');

        Route::controller(DemandeProduitController::class)->group(function () {
            Route::put('/demande-produit-restaure/{demandeproduit}', 'restaureDemandeProduit')->name('demandeproduit.restaure');
            Route::put('/rejeter-demande-produit/{demandeproduit}', 'rejeterDemandeProduit')->name('demandeproduit.rejeter');
            Route::put('/approuver-demande-produit/{demandeproduit}', 'approuverDemandeProduit')->name('demandeproduit.approuver');
        });

        Route::controller(DemandeAccompagnementController::class)->group(function () {
            Route::put('/demande-pret-restaure/{demandeaccompagnement}', 'restaureDemandeAccompagnement')->name('demandeaccompagnement.restaure');
            Route::put('/rejeter-demande-pret/{demandeaccompagnement}', 'rejeterDemandeAccompagnement')->name('demandeaccompagnement.rejeter');
            Route::put('/approuver-demande-pret/{demandeaccompagnement}', 'approuverDemandeAccompagnement')->name('demandeaccompagnement.approuver');
        });

        Route::get('projets-mutualiste/{mutualisteId}', [ProjetMutualisteController::class, 'getProjetMutualistes'])->name('projets.mutualiste');
        Route::get('/produit-details/{produitProjetId}', [ProjetMutualisteController::class, 'getProjetDetails']);

        Route::controller(FacturationController::class)->group(function () {
            Route::get('projets-aquis-mutualiste/{mutualisteId}', 'getProjetAcquisMutualistes')->name('projets.aquis.mutualiste');
            Route::get('/projet-aquis-details/{projetAcquisId}', 'projetAcquisDetails');

            Route::get('redevance-periodes/{redevanceId}', 'getRedevancePeriodes')->name('redevance.periodes');
        });
        Route::get('/detailPaiementCotis/{idCoti}/{idMutual}', [CotisationMutualisteController::class, 'ligneDetailPaiement'])
    ->name('lignePaiement.cotis');

        Route::controller(InteretServiceController::class)->group(function(){
            Route::get('/listes-InteretsService/{id}','indexListe')->name('interetService.liste');
            Route::get('/creation-InteretService/{id}','createInteret')->name('ajouter.interetService');
        });
        Route::controller(DemandeAccompagnementController::class)->group(function(){
            Route::get('/detail-Accompagnement/{id}','detail')->name('demandeAccompagnement.detail');
            Route::post('/accepterDemande-pret/{id}','accepterDemande')->name('accepter.demandePret');
            Route::post('/refuserDemande-Pret/{id}','refusDemandePret')->name('refus.pretDemander');

        });
    });
});
