<?php

namespace App\Http\Requests;

use App\Models\Creneau;
use Illuminate\Foundation\Http\FormRequest;

class RendezVousStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'creneau_id' => [
                'required',
                'integer',
                'exists:creneaux,id',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $creneau = Creneau::find($value);

                    if (! $creneau) {
                        return;
                    }

                    if ($creneau->fin()->lte(now())) {
                        $fail('Ce créneau est déjà passé.');
                        return;
                    }

                    $hasActiveRdv = $creneau->rendezVous()
                        ->where('statut', '!=', 'annule')
                        ->exists();

                    if ($hasActiveRdv) {
                        $fail('Ce créneau est déjà réservé.');
                    }
                },
            ],
        ];
    }
}
