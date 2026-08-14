<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Creneau;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreneauController extends Controller
{
    public function index(): View
    {
        return view('admin.creneaux.index', [
            'creneaux' => Creneau::withCount('rendezVous')
                ->orderBy('date')
                ->orderBy('heure_debut')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.creneaux.form', ['creneau' => new Creneau]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $creneau = new Creneau($data);

        if ($this->chevaucheExistant($creneau)) {
            return back()
                ->withErrors(['date' => 'Ce créneau chevauche un créneau existant.'])
                ->withInput();
        }

        $creneau->save();

        return redirect()
            ->route('admin.creneaux.index')
            ->with('success', 'Créneau créé.');
    }

    public function edit(Creneau $creneau): View
    {
        return view('admin.creneaux.form', compact('creneau'));
    }

    public function update(Request $request, Creneau $creneau): RedirectResponse
    {
        $data = $this->validated($request);

        $propose = new Creneau($data);

        if ($this->chevaucheExistant($propose, $creneau)) {
            return back()
                ->withErrors(['date' => 'Ce créneau chevauche un créneau existant.'])
                ->withInput();
        }

        $creneau->update($data);

        return redirect()
            ->route('admin.creneaux.index')
            ->with('success', 'Créneau mis à jour.');
    }

    public function destroy(Creneau $creneau): RedirectResponse
    {
        $creneau->delete();

        return redirect()
            ->route('admin.creneaux.index')
            ->with('success', 'Créneau supprimé.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'duree_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $data['heure_debut'] .= ':00';

        return $data;
    }

    protected function chevaucheExistant(Creneau $creneau, ?Creneau $ignore = null): bool
    {
        $chevauchements = Creneau::where('date', $creneau->date)
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->get();

        return $chevauchements->contains(fn (Creneau $autre) => $creneau->chevauche($autre));
    }
}
