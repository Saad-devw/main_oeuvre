<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Ouvrier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class DocumentGenerator
{
    private string $tempDir;

    public function __construct()
    {
        $this->tempDir = storage_path('app/temp');
        if (!file_exists($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    /**
     * Generate ONE pdf for all selected ouvriers.
     * For each ouvrier: CRPS + Lettre d'engagement + Etat d'engagement + personal PDF (if any).
     */
    public function generatePdf(Document $generation): string
    {
        $fpdi = new Fpdi();

        foreach ($generation->ouvriers as $ouvrier) {
            // 3 generated pages (Blade -> dompdf)
            $pages = Pdf::loadView('pdf.worker_pages', $this->buildData($ouvrier, $generation))
                ->setPaper('a4')
                ->output();
            $this->appendPdf($fpdi, StreamReader::createByString($pages));

            // Personal documents (uploaded PDF)
            if ($ouvrier->personal_pdf && Storage::disk('local')->exists($ouvrier->personal_pdf)) {
                try {
                    $this->appendPdf($fpdi, StreamReader::createByString(
                        Storage::disk('local')->get($ouvrier->personal_pdf)
                    ));
                } catch (\Throwable $e) {
                    throw new \RuntimeException(
                        "Le PDF personnel de {$ouvrier->full_name} n'a pas pu être fusionné."
                    );
                }
            }
        }

        $path = $this->tempDir . '/documents_'
            . $generation->start_date->format('m_Y') . '_' . $generation->id . '.pdf';

        $fpdi->Output('F', $path);

        return $path;
    }

    /**
     * Append every page of a PDF stream to the FPDI document.
     */
    private function appendPdf(Fpdi $fpdi, $stream): void
    {
        $count = $fpdi->setSourceFile($stream);

        for ($i = 1; $i <= $count; $i++) {
            $tpl  = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($tpl);

            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($tpl);
        }
    }

    /**
     * Data passed to resources/views/pdf/worker_pages.blade.php
     */
    private function buildData(Ouvrier $o, Document $g): array
    {
        // $f = fn ($v) => number_format((float) $v, 2, ',', ' ');
        $f = fn ($v) => number_format((float) $v, 2, ',', "\u{00A0}");
        
        return [
            'name'     => strtoupper($o->full_name),
            'civility' => $o->gender === 'femme' ? 'MME' : 'MR',
            'start'    => $g->start_date->format('d/m/Y'),
            'end'      => $g->end_date->format('d/m/Y'),
            'year'     => $g->start_date->format('Y'),
            'days'     => $g->working_days,
            'sign'     => $g->signature_date?->format('d/m/Y') ?? '……………………',
            'price'    => $f($g->price_per_day),
            'brut'     => $f($g->salaire_brut),
            'annual'   => $f($g->salaire_brut * 12),
            'rcar'     => $f($g->rcar),
            'net'      => $f($g->salaire_net),
            'netWords' => $this->numberToFrenchWords((float) $g->salaire_net),
        ];
    }

    /**
     * Convert a number to French words
     * (e.g. 2162.40 → "Deux Mille Cent Soixante Deux Dirhams Et Quarante Centimes").
     */
    private function numberToFrenchWords(float $amount): string
    {
        $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);

        $dirhams  = (int) floor($amount);
        $centimes = (int) round(($amount - $dirhams) * 100);

        $result = ucwords($formatter->format($dirhams)) . ' Dirhams';
        if ($centimes > 0) {
            $result .= ' Et ' . ucwords($formatter->format($centimes)) . ' Centimes';
        }

        return $result;
    }
}
