@extends('layouts.app')

@section('title', 'Dashboard admin')

@section('content')
    <h2>Tableau de bord</h2>

    <div class="stats">
        <div class="stat-card">
            <div class="value">{{ $stats['total_rdv'] }}</div>
            <div class="label">Rendez-vous</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $stats['creneaux_total'] }}</div>
            <div class="label">Créneaux au total</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $stats['creneaux_disponibles'] }}</div>
            <div class="label">Créneaux disponibles</div>
        </div>
    </div>

    <h3>Tous les rendez-vous</h3>

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Créneau</th>
                <th>Réservé le</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rendezVous as $rdv)
                <tr>
                    <td>{{ $rdv->user->name }}</td>
                    <td>{{ $rdv->user->email }}</td>
                    <td>
                        {{ $rdv->creneau->date }}
                        de {{ \Illuminate\Support\Carbon::parse($rdv->creneau->heure_debut)->format('H:i') }}
                        à {{ $rdv->creneau->fin()->format('H:i') }}
                    </td>
                    <td>{{ $rdv->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucun rendez-vous.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $rendezVous->links() }}
    </div>
@endsection