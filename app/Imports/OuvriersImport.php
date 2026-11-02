<?php

namespace App\Imports;

use App\Models\Ouvrier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OuvriersImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;
    public array $skipped = [];   // ["Ligne 5 : CIN en double dans le fichier (B123456)", ...]

    public function collection(Collection $rows): void
    {
        $seen = [];

        foreach ($rows as $i => $row) {
            $line = $i + 2; // +1 for the heading row, +1 because index starts at 0

            $name   = strtoupper(trim((string) ($row['full_name'] ?? $row['nom_et_prenom'] ?? $row['nom'] ?? '')));
            $cin    = strtoupper(trim((string) ($row['cin'] ?? '')));
            $work   = trim((string) ($row['his_work'] ?? $row['emploi'] ?? $row['travail'] ?? ''));
            $gender = $this->normalizeGender((string) ($row['gender'] ?? $row['genre'] ?? ''));

            // fully empty row → ignore silently
            if ($name === '' && $cin === '' && $work === '') {
                continue;
            }

            if ($name === '' || $cin === '' || $work === '') {
                $this->skipped[] = "Ligne {$line} : nom, CIN ou emploi manquant";
                continue;
            }

            if (!$gender) {
                $this->skipped[] = "Ligne {$line} : genre invalide pour {$name} (utiliser homme/femme ou H/F)";
                continue;
            }

            if (isset($seen[$cin])) {
                $this->skipped[] = "Ligne {$line} : CIN en double dans le fichier ({$cin})";
                continue;
            }

            $seen[$cin] = true;

            // Update if the CIN exists, create otherwise (personal_pdf is left untouched)
            $ouvrier = Ouvrier::updateOrCreate(
                ['cin' => $cin],
                ['full_name' => $name, 'his_work' => $work, 'gender' => $gender]
            );

            $ouvrier->wasRecentlyCreated ? $this->created++ : $this->updated++;
        }
    }

    private function normalizeGender(string $value): ?string
    {
        return match (strtolower(trim($value))) {
            'homme', 'h', 'm', 'masculin', 'male' => 'homme',
            'femme', 'f', 'féminin', 'feminin', 'female' => 'femme',
            default => null,
        };
    }
}
