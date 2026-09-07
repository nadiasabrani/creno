@extends('layouts.app')

@section('title', 'Mes rendez-vous')

@section('content')
    <div class="page-head">
        <h2>Mes rendez-vous</h2>
        <a href="{{ route('creneaux.index') }}" class="btn btn-primary">+ Nouveau rendez-vous</a>
    </div>

    @if ($rendezVous->count())
        <div class="rdv-list">
            @foreach ($rendezVous as $rdv)
                <div class="rdv-card @if($rdv->statut === 'annule') rdv-annule @endif">
                    <div class="rdv-info">
                        <div class="rdv-date">{{ \Carbon\Carbon::parse($rdv->creneau->date)->translatedFormat('l j F Y') }}</div>
                        <div class="rdv-time">
                            {{ $rdv->creneau->debut()->format('H:i') }} – {{ $rdv->creneau->fin()->format('H:i') }}
                        </div>
                        <div class="rdv-meta">
                            <span class="slot-duration">{{ $rdv->creneau->duree_minutes }} min</span>
                        </div>
                    </div>
                    <div class="rdv-actions">
                        @if ($rdv->statut === 'en_attente')
                            <span class="badge badge-yellow">En attente</span>
                        @elseif ($rdv->statut === 'confirme')
                            <span class="badge badge-green">Confirmé</span>
                        @else
                            <span class="badge badge-red">Annulé</span>
                        @endif

                        @if ($rdv->statut !== 'annule' && $rdv->creneau->fin()->gt(now()))
                            <form method="POST" action="{{ route('rendez-vous.annuler', $rdv) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Annuler ce rendez-vous ?');">Annuler</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🗓️</div>
            <p>Vous n'avez aucun rendez-vous.</p>
            <a href="{{ route('creneaux.index') }}" class="btn btn-primary">Voir les créneaux disponibles</a>
        </div>
    @endif
@endsection
