<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Ouvrier;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\IOFactory;
use NumberFormatter;

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
     * Generate a zip containing one .docx per ouvrier (CRPS + Lettre side by side).
     */
    public function generateZip(Document $generation): string
    {
        $zip     = new \ZipArchive();
        $zipName = 'documents_' . $generation->start_date->format('m_Y') . '_' . $generation->id . '.zip';
        $zipPath = $this->tempDir . '/' . $zipName;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Impossible de créer l\'archive zip.');
        }

        foreach ($generation->ouvriers as $ouvrier) {
            $docxPath = $this->generateDocx($ouvrier, $generation);
            $zip->addFile($docxPath, $ouvrier->full_name . '.docx');
        }

        $zip->close();

        // Clean up individual docx files
        foreach ($generation->ouvriers as $ouvrier) {
            $path = $this->tempDir . '/' . $ouvrier->id . '_doc.docx';
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return $zipPath;
    }

    /**
     * Generate a single .docx with two sections: CRPS (left) + Lettre d'engagement (right).
     */
    private function generateDocx(Ouvrier $ouvrier, Document $generation): string
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);

        // ── Shared data ────────────────────────────────────────────────
        $fullName  = strtoupper($ouvrier->full_name);
        $startDate = $generation->start_date->format('d/m/Y');
        $endDate   = $generation->end_date->format('d/m/Y');
        $price     = number_format($generation->price_per_day, 2);
        $brut      = number_format($generation->salaire_brut, 2);
        $net       = number_format($generation->salaire_net, 2);
        $year      = $generation->start_date->format('Y');
        $civility  = $ouvrier->gender === 'femme' ? 'MME' : 'MR';
        $netInLetters = $this->numberToFrenchWords((float) $generation->salaire_net);

        // ── Section 1: COMPTE RENDU DE PRISE DE SERVICE ─────────────────
        $section1 = $phpWord->addSection([
            'marginTop'    => 1000,
            'marginBottom' => 1000,
            'marginLeft'   => 1200,
            'marginRight'  => 720,
            'paperSize'    => 'A4',
        ]);

        // Title box
        $section1->addTable(['borderSize' => 18, 'borderColor' => '000000', 'width' => 5000, 'unit' => 'pct'])
            ->addRow()->addCell(5000, ['bgColor' => 'D9D9D9'])
            ->addText('COMPTE RENDU DE PRISE DE SERVICE', [
                'bold' => true, 'italic' => true, 'size' => 13, 'name' => 'Times New Roman'
            ], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);

        $section1->addTextBreak(2);

        // RECRUTEMENT heading
        $section1->addText('RECRUTEMENT', [
            'bold' => true, 'underline' => 'single', 'size' => 12, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section1->addTextBreak(1);

        // Fields
        $this->addCrpsField($section1, 'NOM ET ', 'PRENOM', ': .................. ' . $fullName);
        $section1->addTextBreak(1);
        $this->addCrpsField($section1, '', 'CATEGORIE', ': .................. OUVRIER (E) NON SPECIALISE (E)');
        $section1->addTextBreak(1);
        $this->addCrpsField($section1, 'EMPLOI A ', 'EXERCER', ': .................. OUVRIER (E) SAISONIER (E)');
        $section1->addTextBreak(1);
        $this->addCrpsField($section1, 'A PRISE SON SERVICE LE :', ' .................. ', ' ' . $startDate);
        $section1->addTextBreak(1);
        $section1->addText('OBSERVATIONS : .................. S/O .................. ', [
            'italic' => true, 'name' => 'Times New Roman', 'size' => 11
        ]);

        $section1->addTextBreak(2);

        // Date line
        $section1->addText(
            'A FOUM EL ANCEUR, LE : .................................',
            ['italic' => true, 'size' => 10, 'name' => 'Times New Roman'],
            ['alignment' => Jc::RIGHT]
        );

        $section1->addTextBreak(1);

        // Signature table
        $sigTable = $section1->addTable([
            'borderSize'  => 6,
            'borderColor' => '000000',
            'width'       => 5000,        // ← fixed width instead of 100*50
            'unit'        => 'pct',
            'alignment'   => Jc::CENTER,  // ← centers the table on the page
        ]);
        $sigTable->addRow(2000);
        $sigTable->addCell(2500, ['valign' => 'top'])  // ← top
            ->addText('SIGNATURE DE L\'INTERESSEE', [
                'italic' => true, 'size' => 9, 'name' => 'Times New Roman'
            ], ['alignment' => Jc::CENTER]);
        $sigTable->addCell(2500, ['valign' => 'top'])  // ← top
            ->addText('SIGNATURE DU CHEF DE SERVICE', [
                'italic' => true, 'size' => 9, 'name' => 'Times New Roman'
            ], ['alignment' => Jc::CENTER]);

        $sigTable->addRow(2500);
        $cell = $sigTable->addCell(5000, ['gridSpan' => 2, 'valign' => 'top']);  // ← top
        $cell->addText('LE PRESIDENT DE LA COMMUNE', [
            'italic' => true, 'size' => 9, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        // ── Section 2: LETTRE D'ENGAGEMENT ────────────────────────────
        $section2 = $phpWord->addSection([
            'marginTop'    => 800,
            'marginBottom' => 800,
            'marginLeft'   => 720,
            'marginRight'  => 720,
            'paperSize'    => 'A4',
            'breakType'    => 'nextPage',  // ← this forces a new page
        ]);

        // Government header (top right)
        $headerLines = [
            'ROYAUME DU MAROC',
            'MINISTERE DE L\'INTERIEUR',
            'PROVINCE DE BENI MELLAL',
            'CERCLE DE BENI MELLAL',
            'CAIDAT DE FOUM EL ANCEUR',
            'COMMUNE DE FOUM EL ANCEUR',
        ];
        foreach ($headerLines as $line) {
            $section2->addText($line, [
                'bold' => true, 'size' => 7, 'name' => 'Times New Roman'
            ], ['alignment' => Jc::LEFT]);
        }

        $section2->addTextBreak(1);

        // Title
        $section2->addText('LETTRE  D\'ENGAGEMENT', [
            'bold' => true, 'underline' => 'single', 'size' => 14, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addTextBreak(1);

        $section2->addText('LE PRESIDENT DE LA COMMUNE DE', [
            'bold' => true, 'size' => 11, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addText('** FOUM EL ANCEUR **', [
            'bold' => true, 'italic' => true, 'size' => 11, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addText('A', [
            'bold' => true, 'size' => 12, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addText($civility . ' / MR : ' . $fullName, [
            'bold' => true, 'italic' => true, 'size' => 12, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addText('** FOUM EL ANCEUR **', [
            'bold' => true, 'italic' => true, 'underline' => 'single', 'size' => 11, 'name' => 'Times New Roman'
        ], ['alignment' => Jc::CENTER]);

        $section2->addTextBreak(1);

        // Body paragraphs
        $para1Style = ['alignment' => Jc::BOTH, 'spaceAfter' => 120];
        $fontStyle  = ['size' => 11, 'name' => 'Times New Roman'];
        $boldFont   = ['size' => 11, 'name' => 'Times New Roman', 'bold' => true];

        // Paragraph 1
        $p1 = $section2->addTextRun($para1Style);
        $p1->addText("\t", $fontStyle);
        $p1->addText('J\'ai l\'honneur de vous Faire connaître que votre Candidature a Eté Acceptée En Conséquence vous Etes Engagé(e) à Compter du : ', $fontStyle);
        $p1->addText($startDate, $boldFont);
        $p1->addText(' en Qualité d\'Ouvrier (e) Saisonnier (e) Non Spécialisé (e) Rétribué sur les Crédits du Section 02  Chapitre  I0 Article 20 Programme 20 Projet / Action 10 Ligne 14 au Titre de l\'Exercice ', $fontStyle);
        $p1->addText($year, $boldFont);
        $p1->addText(' Partant.', $fontStyle);

        // Paragraph 2
        $p2 = $section2->addTextRun($para1Style);
        $p2->addText("\t", $fontStyle);
        $p2->addText('Du ', $fontStyle);
        $p2->addText($startDate, $boldFont);
        $p2->addText(' au ', $fontStyle);
        $p2->addText( $endDate , $boldFont);
        $days = $generation->working_days;
        $p2->addText(" soit {$days} jours de travail avec un repos d'une journée par semaine (lundi jour organisation du souk hebdomadaire à Foum el anceur).", $fontStyle);

        // Paragraph 3
        $p3 = $section2->addTextRun($para1Style);
        $p3->addText("\t", $fontStyle);
        $p3->addText('Vous Percevrez à ce Titre un Salaire Mensuel de : ', $fontStyle);
        $p3->addText( $netInLetters , $boldFont);
        $p3->addText(' ', $fontStyle);

        $p3->addText('soit ', $fontStyle);
        $p3->addText($net, ['size' => 11, 'name' => 'Times New Roman', 'bold' => true]);
        $p3->addText(' DH par jour', ['size' => 11, 'name' => 'Times New Roman']);
        $p3->addText(' Après Déduction de la Part Salariale de la RCAR pour les périodes précitées.', $fontStyle);

        // Paragraph 4
        $p4 = $section2->addTextRun($para1Style);
        $p4->addText("\t", $fontStyle);
        $p4->addText('Si vous Acceptez ces Conditions, je vous Prie de Bien Vouloir vous Présenter avec la Présente Lettre à cette Commune le : ', $fontStyle);
        $p4->addText($startDate, $boldFont);
        $p4->addText(' Pour Prendre Votre Service.', $fontStyle);

        // Paragraph 5
        $p5 = $section2->addTextRun($para1Style);
        $p5->addText("\t", $fontStyle);
        $p5->addText('Veuillez Agréer MME/MR ', $fontStyle);
        $p5->addText($fullName, $boldFont);
        $p5->addText(', l\'Assurance de ma Considération Distinguée.', $fontStyle);

        $section2->addTextBreak(1);

        // Lu et Accepté
        $section2->addText('✓  Lu et Accepté :', ['italic' => true, 'size' => 10, 'name' => 'Times New Roman']);
        $section2->addText('Date de prise de service : ' . $startDate, [
            'italic' => true, 'bold' => true, 'underline' => 'single', 'size' => 10, 'name' => 'Times New Roman'
        ]);

        $section2->addTextBreak(3);

        // Closing
        $section2->addText(
            'A FOUM EL ANCEUR, LE :  ………………………',
            ['bold' => true, 'italic' => true, 'size' => 11, 'name' => 'Times New Roman'],
            ['alignment' => Jc::RIGHT]
        );
        $section2->addTextBreak(1);
        $section2->addText(
            'LE PRESIDENT DE LA COMMUNE',
            ['bold' => true, 'italic' => true, 'size' => 11, 'name' => 'Times New Roman'],
            ['alignment' => Jc::RIGHT]
        );

        // ── Save ───────────────────────────────────────────────────────
        $path = $this->tempDir . '/' . $ouvrier->id . '_doc.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    /**
     * Helper to render an italic line with one underlined keyword.
     */
    private function addCrpsField($section, string $before, string $underlined, string $after): void
    {
        $run = $section->addTextRun();
        if ($before) {
            $run->addText($before, ['italic' => true, 'size' => 11, 'name' => 'Times New Roman']);
        }
        $run->addText($underlined, ['italic' => true, 'underline' => 'single', 'size' => 11, 'name' => 'Times New Roman']);
        $run->addText($after, ['italic' => true, 'size' => 11, 'name' => 'Times New Roman']);
    }

    /**
     * Convert a number to French words (e.g. 2162.40 → "Deux Mille Cent Soixante Deux Dirhams Et Quarante Centimes").
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
