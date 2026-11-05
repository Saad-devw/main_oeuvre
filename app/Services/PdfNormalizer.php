<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class PdfNormalizer
{
    /** Returns true if the PDF can be merged (converting it with Ghostscript if needed). */
    public static function ensureReadable(string $absPath): bool
    {
        try {
            (new Fpdi())->setSourceFile(StreamReader::createByString(file_get_contents($absPath)));
            return true;
        } catch (\Throwable $e) {
            // continue with conversion
        }

        $bin = config('services.ghostscript.path')
            ?: (PHP_OS_FAMILY === 'Windows' ? 'gswin64c' : 'gs');
        $tmp = $absPath . '.tmp.pdf';

        exec(sprintf(
            '%s -q -dNOPAUSE -dBATCH -dSAFER -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -sOutputFile=%s %s 2>&1',
            escapeshellarg($bin), escapeshellarg($tmp), escapeshellarg($absPath)
        ), $out, $code);

        if ($code === 0 && file_exists($tmp)) {
            rename($tmp, $absPath);
            return true;
        }

        @unlink($tmp);
        return false;
    }
}
