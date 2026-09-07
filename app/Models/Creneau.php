<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Creneau extends Model
{
    use HasFactory;

    protected $table = 'creneaux';

    protected $fillable = ['date', 'heure_debut', 'duree_minutes'];

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }

    public function debut(): Carbon
    {
        return Carbon::parse($this->date.' '.$this->heure_debut);
    }

    public function fin(): Carbon
    {
        return $this->debut()->addMinutes($this->duree_minutes);
    }

    public function scopePasses(Builder $query): Builder
    {
        $today = now()->toDateString();
        $now = now()->format('H:i:s');

        $finExpr = $this->finExpression();

        return $query->whereRaw(
            "(date < ?) OR (date = ? AND {$finExpr} <= ?)",
            [$today, $today, $now]
        );
    }

    public function scopeDisponibles(Builder $query): Builder
    {
        $today = now()->toDateString();
        $now = now()->format('H:i:s');

        $finExpr = $this->finExpression();

        return $query
            ->whereDoesntHave('rendezVous', fn ($q) => $q->where('statut', '!=', 'annule'))
            ->whereRaw(
                "(date > ?) OR (date = ? AND {$finExpr} > ?)",
                [$today, $today, $now]
            );
    }

    public function chevauche(Creneau $autre): bool
    {
        if ($this->date != $autre->date) {
            return false;
        }

        return $this->heure_debut < $autre->fin()->format('H:i:s')
            && $this->fin()->format('H:i:s') > $autre->heure_debut;
    }

    /**
     * Expression SQL calculant l'heure de fin du créneau,
     * compatible MySQL et SQLite.
     */
    protected function finExpression(): string
    {
        $driver = $this->getConnection()->getDriverName();

        if ($driver === 'mysql') {
            return "ADDTIME(heure_debut, SEC_TO_TIME(duree_minutes * 60))";
        }

        // SQLite
        return "time(datetime(date || ' ' || heure_debut, '+' || duree_minutes || ' minutes'))";
    }
}
