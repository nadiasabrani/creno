<?php

namespace App\Http\Controllers;

use App\Http\Requests\RendezVousStoreRequest;
use App\Models\Creneau;
use App\Models\RendezVous;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RendezVousController extends Controller
{
    public function index(): View
    {
        return view('creneaux.index', [
            'creneaux' => Creneau::disponibles()
                ->orderBy('date')
                ->orderBy('heure_debut')
                ->get(),
        ]);
    }

    public function store(RendezVousStoreRequest $request): RedirectResponse
    {
        $creneau = Creneau::findOrFail($request->validated('creneau_id'));

        // Vérifier que le créneau n'est pas passé (double vérification après FormRequest)
        if ($creneau->fin()->lte(now())) {
            return back()
                ->withErrors(['creneau_id' => 'Ce créneau est déjà passé.'])
                ->withInput();
        }

        // Vérifier chevauchement avec les rendez-vous existants de l'utilisateur
        $userId = auth()->id();
        $rdvExistants = RendezVous::where('user_id', $userId)
            ->where('statut', '!=', 'annule')
            ->with('creneau')
            ->get();

        foreach ($rdvExistants as $rdv) {
            if ($creneau->chevauche($rdv->creneau)) {
                return back()
                    ->withErrors(['creneau_id' => 'Vous avez déjà un rendez-vous qui chevauche ce créneau.'])
                    ->withInput();
            }
        }

        // Réservation atomique pour gérer la concurrence (double clic)
        try {
            DB::transaction(function () use ($creneau, $userId) {
                // Verrouiller les rendez-vous existants pour ce créneau
                $existing = RendezVous::where('creneau_id', $creneau->id)
                    ->where('statut', '!=', 'annule')
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    abort(409, 'Ce créneau vient d\'être réservé par un autre utilisateur.');
                }

                RendezVous::create([
                    'creneau_id' => $creneau->id,
                    'user_id' => $userId,
                    'statut' => RendezVous::STATUT_EN_ATTENTE,
                ]);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return back()
                ->withErrors(['creneau_id' => 'Ce créneau vient d\'être réservé par un autre utilisateur.'])
                ->withInput();
        }

        return redirect()
            ->route('rendez-vous.mine')
            ->with('success', 'Rendez-vous réservé avec succès.');
    }

    public function mesRendezVous(): View
    {
        return view('rendez-vous.index', [
            'rendezVous' => RendezVous::where('user_id', auth()->id())
                ->with('creneau')
                ->latest()
                ->get(),
        ]);
    }

    public function annuler(RendezVous $rendezVous): RedirectResponse
    {
        if ($rendezVous->user_id !== auth()->id()) {
            abort(403);
        }

        $rendezVous->update(['statut' => RendezVous::STATUT_ANNULE]);

        return redirect()
            ->route('rendez-vous.mine')
            ->with('success', 'Rendez-vous annulé.');
    }
}
