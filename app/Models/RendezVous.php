<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = ['creneau_id', 'user_id'];

    public function creneau(): BelongsTo
    {
        return $this->belongsTo(Creneau::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
