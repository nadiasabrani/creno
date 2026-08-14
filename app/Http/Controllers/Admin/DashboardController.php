<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Creneau;
use App\Models\RendezVous;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $rendezVous = RendezVous::with(['creneau', 'user'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_rdv' => RendezVous::count(),
            'creneaux_total' => Creneau::count(),
            'creneaux_disponibles' => Creneau::disponibles()->count(),
        ];

        return view('admin.dashboard', compact('rendezVous', 'stats'));
    }
}
