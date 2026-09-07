<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory;

    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_CONFIRME = 'confirme';
    public const STATUT_ANNULE = 'annule';

    protected $table = 'rendez_vous';

    protected $fillable = ['creneau_id', 'user_id', 'statut'];

    public function creneau(): BelongsTo
    {
        return $this->belongsTo(Creneau::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('statut', '!=', self::STATUT_ANNULE);
    }
}
