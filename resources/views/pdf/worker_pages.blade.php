<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Times', serif;
            font-size: 11pt;
            margin: 0;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .it {
            font-style: italic;
        }

        .b {
            font-weight: bold;
        }

        p {
            margin: 0 0 10px 0;
        }

        /* ───────── PAGE 1 : COMPTE RENDU ───────── */
        .p1 {
            padding: 26pt 57pt 0 73pt;
            font-family: Helvetica, Arial, sans-serif;
        }

        .p1-title {
            width: 280pt;
            margin: 0 auto;
            background: #d0d0d0;
            border-top: 1pt solid #e8e8e8;
            border-left: 1pt solid #e8e8e8;
            border-right: 1.5pt solid #8c8c8c;
            border-bottom: 1.5pt solid #8c8c8c;
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            font-style: italic;
            padding-top: 10pt;
            padding-bottom: 10pt;
        }

        .p1-rec {
            margin-top: 22pt;
            height: 16pt;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
        }

        /* .p1-f {
            height: 44pt;
            line-height: 14pt;
            font-size: 9pt;
            font-weight: bold;
            font-style: italic;
            white-space: nowrap;
        } */

        .p1-t { width: 100%; border-collapse: collapse; margin-top: 4pt; border: 1pt solid #000; }
        .p1-t td { height: 32pt; font-size: 10pt; font-weight: bold; font-style: italic;
                vertical-align: middle; border: 0.6pt solid #000; }
        .p1-t td.l { width: 36%; text-align: left; padding-left: 10pt; white-space: nowrap;
                    background: #e6e6e6; }
        .p1-t td.r { width: 64%; text-align: center; background: #fff; }

        .p1-date {
            margin-top: 14pt;
            text-align: right;
            font-size: 7.5pt;
            font-weight: bold;
            font-style: italic;
        }

        .p1-sig {
            width: 466pt;
            border-collapse: collapse;
            margin-top: 9pt;
        }

        .p1-sig td {
            border: 1pt solid #000;
            text-align: center;
            vertical-align: top;
            padding-top: 7pt;
            font-size: 7.5pt;
            font-weight: bold;
            font-style: italic;
        }

        /* ───────── PAGE 2 : LETTRE ───────── */
        .p2 {
            padding: 40pt 50pt;
        }

        .just {
            text-align: justify;
            text-indent: 35px;
            margin-bottom: 8px;
        }

        /* ───────── PAGE 3 : ETAT D'ENGAGEMENT ───────── */
        .p3 {
            padding: 40pt 0 0 30pt;
            font-family: Helvetica, Arial, sans-serif;
        }
        .etat {
            width: 535pt;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.5pt;
            border: 1.2pt double #000;
        }

        .etat td {
            border: 0.6pt double #000;
            padding: 0 3pt;
            height: 14pt;
            white-space: nowrap;
            overflow: hidden;
            vertical-align: middle;
        }

        .etat tr.sp td {
            border: none;
            padding: 0;
            height: 0;
            font-size: 0;
            line-height: 0;
        }

        .etat td.lb {
            font-weight: bold;
            font-size: 6.8pt;
            padding: 0 2pt;
        }

        .etat td.n {
            padding: 0 1pt;
        }

        .etat td.sm {
            font-size: 6pt;
            white-space: normal;
            line-height: 7pt;
        }

        .etat td.sm6 {
            font-size: 6.5pt;
            padding: 0 1pt;
        }

        /* fully gray rows: every cell of the row is gray */
        .etat tr.gr td {
            background: #c8c8c8;
            font-weight: bold;
        }

        .etat tr.title td {
            background: #a6a6a6;
            font-size: 10.5pt;
            font-weight: normal;
            letter-spacing: 1pt;
            height: 22pt;
        }

        .bd { font-weight: bold; }
        .c  { text-align: center; }
        .v  { text-align: center; font-weight: bold; }

        /* footer: plain text, no borders, one left and one right on the same line */
        .foot {
            width: 535pt;
            border-collapse: collapse;
            margin-top: 15pt;
            font-size: 8.5pt;
            font-weight: bold;
        }

        .foot td { border: none; padding: 0; }
        .foot td.fl { text-align: left; }
        .foot td.fr { text-align: right; }
    </style>
</head>

<body>

    {{-- ═════════ PAGE 1 : COMPTE RENDU DE PRISE DE SERVICE ═════════ --}}
    <div class="page p1">
        <div style="font-size:7pt; font-weight:bold; line-height:1.3">
            ROYAUME DU MAROC<br>MINISTERE DE L'INTERIEUR<br>PROVINCE DE BENI MELLAL<br>
            CERCLE DE BENI MELLAL<br>CAIDAT DE FOUM EL ANCEUR<br>COMMUNE DE FOUM EL ANCEUR
        </div>
        <br>
        <br>
        <br>
        <div class="p1-title">COMPTE RENDU DE PRISE DE SERVICE</div>

        <div class="p1-rec">RECRUTEMENT</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>

        <table class="p1-t">
            <tr>
                <td class="l">* NOM ET PRENOM :</td>
                <td class="r">{{ $name }}</td>
            </tr>
            <tr>
                <td class="l">* CATEGORIE :</td>
                <td class="r">OUVRIER (E) NON SPECIALISE (E)</td>
            </tr>
            <tr>
                <td class="l">* EMPLOI A EXERCER :</td>
                <td class="r">OUVRIER (E) SAISONIER (E)</td>
            </tr>
            <tr>
                <td class="l">* PRISE DE SERVICE :</td>
                <td class="r">{{ $start }}</td>
            </tr>
            <tr>
                <td class="l">* OBSERVATIONS :</td>
                <td class="r">S/O</td>
            </tr>
        </table>
        <div>&nbsp;</div><div>&nbsp;</div>
        <div class="p1-date">A FOUM EL ANCEUR, LE : ...................................................</div>

        <table class="p1-sig">
            <tr style="height:120pt;">
                <td style="width:50%; height:120pt;">
                    SIGNATURE DE L'INTERESSE
                </td>
                <td style="width:50%; height:120pt;">
                    SIGNATURE DU CHEF DE SERVICE
                </td>
            </tr>

            <tr style="height:180pt;">
                <td colspan="2" style="height:180pt;">LE PRESIDENT DE LA COMMUNE</td>
            </tr>
        </table>
    </div>

    {{-- ═════════ PAGE 2 : LETTRE D'ENGAGEMENT ═════════ --}}
    <div class="page p2">
        <div style="font-size:7pt; font-weight:bold; line-height:1.3">
            ROYAUME DU MAROC<br>MINISTERE DE L'INTERIEUR<br>PROVINCE DE BENI MELLAL<br>
            CERCLE DE BENI MELLAL<br>CAIDAT DE FOUM EL ANCEUR<br>COMMUNE DE FOUM EL ANCEUR
        </div>
        <br>
        <br>
        <br>
        <p class="center b" style="font-size:14pt; font-weight: bold;"><u>LETTRE &nbsp;D'ENGAGEMENT</u></p>
        <div>&nbsp;</div>
        <p class="center b" style="font-size:10pt">LE PRESIDENT DE LA COMMUNE DE<br><i>** FOUM EL ANCEUR **</i></p>
        <p class="center b" style="font-size:10pt;">A</p>
        <p class="center b it" style="font-size:10pt;">{{ $civility }} / MR : {{ $name }}</p>
        <p class="center b it" style="font-size:10pt;">** FOUM EL ANCEUR **</p>
        <br>
        <p class="just">J'ai l'honneur de vous Faire connaître que votre Candidature a Eté Acceptée En Conséquence
            vous Etes Engagé(e) à Compter du : <b>{{ $start }}</b> en Qualité d'Ouvrier (e) Saisonnier (e) Non
            Spécialisé (e) Rétribué sur les Crédits du Section 02 Chapitre I0 Article 20 Programme 20 Projet / Action 10
            Ligne 14 au Titre de l'Exercice <b>{{ $year }}</b> Partant.</p>
        <p class="just">Du <b>{{ $start }}</b> au <b>{{ $end }}</b> soit <b>{{ $days }}</b>
            jours de travail avec un repos d'une journée par semaine (Lundi souk hebdomadaire de foum el anceur).</p>
        <p class="just">Vous Percevrez à ce Titre un Salaire Mensuel de : <b>{{ $netWords }}</b>
            (<b>{{ $net }}&nbsp;DH</b>) soit (<b>{{ $price }}&nbsp;DH&nbsp;par&nbsp;jour</b>) Après Déduction de la Part
            Salariale de la RCAR pour les périodes précitées.</p>
        <p class="just">Si vous Acceptez ces Conditions, je vous Prie de Bien Vouloir vous Présenter avec la Présente
            Lettre à cette Commune le : <b>{{ $start }}</b> Pour Prendre Votre Service.</p>
        <p class="just">Veuillez Agréer MME/MR <b>{{ $name }}</b>, l'Assurance de ma Considération
            Distinguée.</p>
        <br>
        <br>
        <p class="it" style="font-size:10pt; font-weight: bold;"><span style="font-family:'DejaVu Sans'">✓</span> &nbsp;Lu et Accepté :
        </p>
        <br><br>
        <br><br>
        <p class="right b it" style="font-size:9pt;">A FOUM EL ANCEUR, LE : {{ $sign }}</p>
        <p class="right b it" style="font-size:8pt;">LE PRESIDENT DE LA COMMUNE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
    </div>

    {{-- ═════════ PAGE 3 : ETAT D'ENGAGEMENT ═════════ --}}
    <div class="page p3">
        <table class="etat">
            {{-- column grid (8 columns, 535pt) --}}
            <tr class="sp">
                <td style="width:58pt"></td>
                <td style="width:80pt"></td>
                <td style="width:38pt"></td>
                <td style="width:90pt"></td>
                <td style="width:28pt"></td>
                <td style="width:34pt"></td>
                <td style="width:159pt"></td>
                <td style="width:48pt"></td>
            </tr>

            <tr>
                <td colspan="8" class="c bd" style="font-size:10pt; height:32pt">COMMUNE DE FOUM EL ANCEUR</td>
            </tr>
            <tr class="gr title">
                <td colspan="8" class="c">ETAT D'ENGAGEMENT</td>
            </tr>

            <tr style="height:24pt">
                <td class="lb">EXERCICE</td>
                <td class="v">{{ $year }}</td>
                <td colspan="3" class="c bd">SITUATION DE FAMILLE</td>
                <td class="c bd sm n">NOM ET PRENOM</td>
                <td colspan="2" class="v">{{ $name }}</td>
            </tr>
            <tr>
                <td class="lb">SECTION</td>
                <td class="v">2</td>
                <td></td>
                <td class="c bd">ANC</td>
                <td class="c bd n">NOUV</td>
                <td class="c bd sm n" rowspan="7">GRADE/<br>CATEG</td>
                <td colspan="2" class="bd">OUVRIER (e) SAISONNIER (e)</td>
            </tr>
            <tr>
                <td class="lb">CHAPITRE</td>
                <td class="v">10</td>
                <td class="bd n">ETAT</td>
                <td></td>
                <td></td>
                <td class="bd">ECHELLE</td>
                <td class="c bd">/</td>
            </tr>
            <tr>
                <td class="lb">ARTICLE</td>
                <td class="v">20</td>
                <td class="bd sm6">BR ENFAN</td>
                <td></td>
                <td></td>
                <td class="bd">ECHELLON</td>
                <td></td>
            </tr>
            <tr>
                <td class="lb">PARAGRAPHE</td>
                <td class="v">10</td>
                <td class="bd" rowspan="2">NATURE</td>
                <td class="c bd">SOLDE</td>
                <td></td>
                <td colspan="2" class="c bd">INDICE</td>
            </tr>
            <tr>
                <td class="lb">LIGNE</td>
                <td class="v">14</td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="lb">DATE D'EFFET</td>
                <td class="v">{{ $start }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="lb">DATE FIN</td>
                <td class="v">{{ $end }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="lb">CREDIT</td>
                <td></td>
                <td colspan="3" class="bd">ANCEINETE</td>
                <td></td>
                <td class="bd">ECHELLE</td>
                <td></td>
            </tr>
            <tr>
                <td class="lb">MUTUELLE</td>
                <td></td>
                <td colspan="3"></td>
                <td></td>
                <td class="bd">ECHELLON</td>
                <td></td>
            </tr>
            <tr>
                <td class="lb">CAISSE</td>
                <td class="bd c">RCAR &nbsp;&nbsp;&nbsp;&nbsp; RCAR</td>
                <td colspan="3"></td>
                <td></td>
                <td class="bd">INDICE</td>
                <td class="c bd">/</td>
            </tr>

            <tr style="height:26pt">
                <td colspan="2" class="c bd">NATURE DES ELEMENTS MODIFIES</td>
                <td colspan="3" class="c bd">MONTANT ANNUEL<br>ANCIEN</td>
                <td colspan="3" class="c bd"><u>NOTES PARTICULIERES</u></td>
            </tr>
            <tr>
                <td colspan="2">TRAITEMENT DE BASE...................</td>
                <td></td>
                <td class="v">{{ $annual }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">INDEMNITE COMPENSATRICE.........</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">INDEMNITE RESPONSABILITE.........</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">SUPP. DU SALAIRE........................</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3" rowspan="5" class="v"
                    style="white-space:normal; line-height:10pt; padding:0 6pt;">reglement salaire du
                    {{ $start }} au {{ $end }} avec un repos d'une journée par semaine (Lundi souk hebdomadaire de foum el anceur)</td>
            </tr>
            <tr>
                <td colspan="2">PRIME ANCIENNETE.......................</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">T.P.S.....................................</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">INDEMNITE SPECIALE...............</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">PRIME ANCIENNETE</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">ALLOCATION HERARCHIQUE...........</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;PRIME DE SUJESTION................</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>

            {{-- fully gray row --}}
            <tr class="gr">
                <td colspan="2" class="c">TOTAL</td>
                <td></td>
                <td class="v">{{ $annual }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;ALLOCATION FAMILLIALE.........</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            {{-- fully gray row --}}
            <tr class="gr">
                <td colspan="2" class="c">EMOLUMENTS ANNUEL</td>
                <td></td>
                <td class="v">{{ $annual }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            {{-- fully gray row --}}
            <tr class="gr">
                <td colspan="2" class="c">TOTAL EMOLUMENTS MENSUEL</td>
                <td></td>
                <td class="v">{{ $brut }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td colspan="2">I.G.R....................................</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3" class="bd">MONTANTS</td>
            </tr>
            <tr>
                <td colspan="2">VALID.SERVICE ANT.................</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">RACHAT...................................</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="lb">MUTIELLE</td>
                <td>S.C...........</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bd n">BRUT</td>
                <td class="v">{{ $brut }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="lb">OMFAM</td>
                <td>S.M...........</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bd n">RCAR</td>
                <td class="v">{{ $rcar }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="lb">OU GENERAL</td>
                <td>C.C.D/C.A.D</td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">C.M.R...................................</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bd n">NET</td>
                <td class="v">{{ $net }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">R.C.A.R................................</td>
                <td></td>
                <td class="v">{{ $rcar }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="2">PRELEVEMENT ..................</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bd n">SOIT</td>
                <td class="v">{{ $days }}</td>
                <td class="bd c">JOURS</td>
            </tr>
            {{-- fully gray row --}}
            <tr class="gr" style="height:26pt">
                <td colspan="2" class="c">NET A ORDONANCER</td>
                <td></td>
                <td class="v">{{ $net }}</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
        </table>

        {{-- Footer: plain text, no borders --}}
        <table class="foot">
            <tr>
                <td class="fl">A FOUM EL ANCEUR, LE : {{ $sign }}</td>
                <td class="fr">LE PRESIDENT</td>
            </tr>
        </table>
    </div>

</body>

</html>
