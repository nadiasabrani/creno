@extends('layouts.app')

@section('title', 'Créneaux')

@section('content')
    <div class="page-head">
        <h2>Créneaux</h2>
        <a href="{{ route('admin.creneaux.create') }}" class="btn btn-primary">Nouveau créneau</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Durée</th>
                <th>Rendez-vous</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($creneaux as $creneau)
                <tr>
                    <td>{{ $creneau->date }}</td>
                    <td>{{ $creneau->debut()->format('H:i') }}</td>
                    <td>{{ $creneau->fin()->format('H:i') }}</td>
                    <td>{{ $creneau->duree_minutes }} min</td>
                    <td>
                        @if ($creneau->rendez_vous_count > 0)
                            <span class="badge badge-red">{{ $creneau->rendez_vous_count }} réservé(s)</span>
                        @else
                            <span class="badge badge-green">Disponible</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.creneaux.edit', $creneau) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        <form method="POST" action="{{ route('admin.creneaux.destroy', $creneau) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce créneau ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun créneau pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection