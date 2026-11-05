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
    /** Number of workers per PDF file (lower it if memory errors persist). */
    private const CHUNK = 12;

    private string $tempDir;

    public function __construct()
    {
        $this->tempDir = storage_path('app/temp');
        if (!file_exists($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    /**
     * Returns the path of a single .pdf (12 workers or fewer)
     * or of a .zip containing several PDF parts.
     */
    public function generate(Document $generation): string
    {
        $workers = $generation->ouvriers->sortBy('full_name')->values();
        $chunks  = $workers->chunk(self::CHUNK);
        $base    = 'documents_' . $generation->start_date->format('m_Y') . '_' . $generation->id;

        if ($chunks->count() <= 1) {
            return $this->buildPdf($workers, $generation, $base . '.pdf');
        }

        $zipPath = $this->tempDir . '/' . $base . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Impossible de créer l'archive zip.");
        }

        $parts = [];
        foreach ($chunks as $i => $chunk) {
            $name    = $base . '_partie' . ($i + 1) . '.pdf';
            $path    = $this->buildPdf($chunk->values(), $generation, $name);
            $parts[] = $path;
            $zip->addFile($path, $name);
            gc_collect_cycles();
        }

        $zip->close();

        foreach ($parts as $p) {
            @unlink($p);
        }

        return $zipPath;
    }

    /**
     * One PDF for a group of workers:
     * CRPS + Lettre d'engagement + Etat d'engagement + personal PDF, for each worker.
     */
    private function buildPdf($workers, Document $generation, string $fileName): string
    {
        $fpdi = new Fpdi();

        foreach ($workers as $ouvrier) {
            $pages = Pdf::loadView('pdf.worker_pages', $this->buildData($ouvrier, $generation))
                ->setPaper('a4')
                ->output();
            $this->appendPdf($fpdi, StreamReader::createByString($pages));
            unset($pages);

            if ($ouvrier->personal_pdf && Storage::disk('local')->exists($ouvrier->personal_pdf)) {
                $this->appendPersonalPdf($fpdi, $ouvrier->personal_pdf, $ouvrier->full_name);
            }

            gc_collect_cycles();
        }

        $path = $this->tempDir . '/' . $fileName;
        $fpdi->Output('F', $path);
        unset($fpdi);
        gc_collect_cycles();

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
     * Merge the worker's personal PDF. If FPDI can't read it (newer compression),
     * re-write it as PDF 1.4 with Ghostscript (when available) and retry.
     */
    private function appendPersonalPdf(Fpdi $fpdi, string $relativePath, string $workerName): void
    {
        try {
            $this->appendPdf($fpdi, StreamReader::createByString(
                Storage::disk('local')->get($relativePath)
            ));
            return;
        } catch (\Throwable $first) {
            $converted = $this->convertWithGhostscript(Storage::disk('local')->path($relativePath));

            if (!$converted) {
                throw new \RuntimeException(
                    "Le PDF personnel de {$workerName} n'a pas pu être fusionné : " . $first->getMessage()
                );
            }

            try {
                $this->appendPdf($fpdi, StreamReader::createByString(file_get_contents($converted)));
            } catch (\Throwable $second) {
                throw new \RuntimeException(
                    "Le PDF personnel de {$workerName} n'a pas pu être fusionné : " . $second->getMessage()
                );
            } finally {
                @unlink($converted);
            }
        }
    }

    private function convertWithGhostscript(string $input): ?string
    {
        if (!function_exists('exec')) {
            return null;
        }

        $bin = config('services.ghostscript.path')
            ?: (PHP_OS_FAMILY === 'Windows' ? 'gswin64c' : 'gs');
        $out = $this->tempDir . '/gs_' . uniqid() . '.pdf';

        $cmd = sprintf(
            '%s -q -dNOPAUSE -dBATCH -dSAFER -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -sOutputFile=%s %s 2>&1',
            escapeshellarg($bin),
            escapeshellarg($out),
            escapeshellarg($input)
        );

        @exec($cmd, $output, $code);

        return ($code === 0 && file_exists($out)) ? $out : null;
    }

    /**
     * Data passed to resources/views/pdf/worker_pages.blade.php
     */
    private function buildData(Ouvrier $o, Document $g): array
    {
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
