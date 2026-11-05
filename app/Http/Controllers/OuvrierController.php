<?php

namespace App\Http\Controllers;

use App\Models\Ouvrier;
use App\Imports\OuvriersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfNormalizer;

class OuvrierController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Veuillez choisir un fichier Excel.',
            'file.mimes'    => 'Le fichier doit être au format .xlsx, .xls ou .csv.',
        ]);

        $import = new OuvriersImport();
        Excel::import($import, $request->file('file'));

        if ($request->expectsJson()) {
            return response()->json([
                'created' => $import->created,
                'updated' => $import->updated,
                'errors'  => $import->skipped,
            ]);
        }

        return redirect()->route('ouvriers.index')
            ->with('success', "{$import->created} ajouté(s), {$import->updated} mis à jour."
                . (count($import->skipped) ? ' ' . count($import->skipped) . ' ligne(s) ignorée(s).' : ''))
            ->with('import_errors', $import->skipped);
    }

    public function bulkPdf(Request $request)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|mimes:pdf|max:10240',
        ]);

        $ok = 0;
        $errors = [];

        foreach ($request->file('files') as $file) {
            $name = $file->getClientOriginalName();
            $cin  = strtoupper(pathinfo($name, PATHINFO_FILENAME));

            $ouvrier = Ouvrier::where('cin', $cin)->first();
            if (!$ouvrier) {
                $errors[] = "{$name} : aucun ouvrier avec le CIN {$cin}";
                continue;
            }

            $path = $file->store('ouvriers', 'local');
            if (!PdfNormalizer::ensureReadable(Storage::disk('local')->path($path))) {
                Storage::disk('local')->delete($path);
                $errors[] = "{$name} : PDF illisible (conversion impossible)";
                continue;
            }

            if ($ouvrier->personal_pdf) {
                Storage::disk('local')->delete($ouvrier->personal_pdf);
            }
            $ouvrier->update(['personal_pdf' => $path]);
            $ok++;
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => $ok, 'errors' => $errors]);
        }

        return redirect()->route('ouvriers.index')
            ->with('success', "{$ok} PDF associé(s).")
            ->with('import_errors', $errors);
    }

    public function index()
    {
        $ouvriers = Ouvrier::orderBy('id')->paginate(20);

        // totals for the whole table, independent of the page
        $counts = Ouvrier::selectRaw('gender, COUNT(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $stats = [
            'total'  => $counts->sum(),
            'hommes' => $counts['homme'] ?? 0,
            'femmes' => $counts['femme'] ?? 0,
        ];

        return view('pages.ouvriers.index', compact('ouvriers', 'stats'));
    }

    public function create()
    {
        return view('pages.ouvriers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:255',
            'cin'          => 'required|string|max:20|unique:ouvriers,cin',
            'his_work'     => 'required|string|max:255',
            'gender'       => 'required|in:homme,femme',
            'personal_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'cin.unique' => 'Ce CIN est déjà enregistré.',
        ]);

        $data['full_name'] = strtoupper($data['full_name']);
        $data['cin']       = strtoupper($data['cin']);

        if ($request->hasFile('personal_pdf')) {
            $path = $request->file('personal_pdf')->store('ouvriers', 'local');

            if (!PdfNormalizer::ensureReadable(Storage::disk('local')->path($path))) {
                Storage::disk('local')->delete($path);
                return back()->withInput()->withErrors([
                    'personal_pdf' => "Ce PDF n'est pas lisible. Réenregistrez-le avec « Imprimer en PDF » puis réessayez.",
                ]);
            }

            if (isset($ouvrier) && $ouvrier->personal_pdf) {   // update only
                Storage::disk('local')->delete($ouvrier->personal_pdf);
            }
            $data['personal_pdf'] = $path;
        } else {
            unset($data['personal_pdf']);
        }

        Ouvrier::create($data);

        return redirect()->route('ouvriers.index')
            ->with('success', 'Ouvrier ' . $data['full_name'] . ' ajouté avec succès.');
    }

    public function edit(Ouvrier $ouvrier)
    {
        return view('pages.ouvriers.create', compact('ouvrier'));
    }

    public function update(Request $request, Ouvrier $ouvrier)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:255',
            'cin'          => 'required|string|max:20|unique:ouvriers,cin,' . $ouvrier->id,
            'his_work'     => 'required|string|max:255',
            'gender'       => 'required|in:homme,femme',
            'personal_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'cin.unique' => 'Ce CIN est déjà enregistré.',
        ]);

        $data['full_name'] = strtoupper($data['full_name']);
        $data['cin']       = strtoupper($data['cin']);

        if ($request->hasFile('personal_pdf')) {
            $path = $request->file('personal_pdf')->store('ouvriers', 'local');

            if (!PdfNormalizer::ensureReadable(Storage::disk('local')->path($path))) {
                Storage::disk('local')->delete($path);
                return back()->withInput()->withErrors([
                    'personal_pdf' => "Ce PDF n'est pas lisible. Réenregistrez-le avec « Imprimer en PDF » puis réessayez.",
                ]);
            }

            if (isset($ouvrier) && $ouvrier->personal_pdf) {   // update only
                Storage::disk('local')->delete($ouvrier->personal_pdf);
            }
            $data['personal_pdf'] = $path;
        } else {
            unset($data['personal_pdf']);
        }

        $ouvrier->update($data);

        return redirect()->route('ouvriers.index')
            ->with('success', 'Ouvrier modifié avec succès.');
    }

    public function destroy(Ouvrier $ouvrier)
    {
        $name = $ouvrier->full_name;
        $ouvrier->delete();
        if ($ouvrier->personal_pdf){
            \Storage::disk('local')->delete($ouvrier->personal_pdf);
        }

        return redirect()->route('ouvriers.index')->with('success', $name . ' supprimé.');
    }
}
