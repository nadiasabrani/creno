@extends('layouts.app')

@section('title', 'Créneaux disponibles')

@section('content')
    <div class="page-head">
        <h2>Créneaux disponibles</h2>
    </div>

    @if ($creneaux->count())
        <div class="slot-grid">
            @foreach ($creneaux as $creneau)
                <div class="slot-card">
                    <div class="slot-date">{{ \Carbon\Carbon::parse($creneau->date)->translatedFormat('l j F Y') }}</div>
                    <div class="slot-time">{{ $creneau->debut()->format('H:i') }} – {{ $creneau->fin()->format('H:i') }}</div>
                    <div class="slot-duration">{{ $creneau->duree_minutes }} minutes</div>
                    <form method="POST" action="{{ route('rendez-vous.store') }}">
                        @csrf
                        <input type="hidden" name="creneau_id" value="{{ $creneau->id }}">
                        <button type="submit" class="btn btn-primary">Réserver ce créneau</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <p>Aucun créneau disponible pour le moment.</p>
        </div>
    @endif
@endsection
