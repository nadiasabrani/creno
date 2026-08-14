@extends('layouts.app')

@section('title', $creneau->exists ? 'Modifier le créneau' : 'Nouveau créneau')

@section('content')
    <h2>{{ $creneau->exists ? 'Modifier le créneau' : 'Nouveau créneau' }}</h2>

    <form method="POST" action="{{ $creneau->exists ? route('admin.creneaux.update', $creneau) : route('admin.creneaux.store') }}">
        @csrf
        @if ($creneau->exists)
            @method('PUT')
        @endif

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="{{ old('date', $creneau->date) }}" required>
        @error('date')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <label for="heure_debut">Heure de début</label>
        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut', $creneau->exists ? $creneau->debut()->format('H:i') : '') }}" required>
        @error('heure_debut')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <label for="duree_minutes">Durée (minutes)</label>
        <input type="number" id="duree_minutes" name="duree_minutes" value="{{ old('duree_minutes', $creneau->duree_minutes) }}" min="1" required>
        @error('duree_minutes')
            <p class="form-error">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn btn-primary">{{ $creneau->exists ? 'Enregistrer' : 'Créer' }}</button>
        <a href="{{ route('admin.creneaux.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection