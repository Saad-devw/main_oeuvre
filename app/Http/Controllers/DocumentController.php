<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Ouvrier;
use App\Services\DocumentGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index()
    {
        $employees = Ouvrier::orderBy('id')->get();
        return view('pages.recrutement.index', compact('employees'));
    }

    public function generate(Request $request)
    {
        // Generating many PDFs needs more time and memory than the defaults
        @ini_set('memory_limit', '1024M');
        @set_time_limit(0);

        $request->validate([
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'signature_date' => ['nullable', 'date'],
            'price_per_day'  => ['required', 'numeric', 'min:1'],
            'working_days'   => ['required', 'integer', 'min:1', 'max:31'],
            'employee_ids'   => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:ouvriers,id'],
        ], [
            'start_date.required'     => 'La date de début est obligatoire.',
            'end_date.required'       => 'La date de fin est obligatoire.',
            'end_date.after_or_equal' => 'La date de fin doit être après la date de début.',
            'price_per_day.required'  => 'Le prix par jour est obligatoire.',
            'price_per_day.min'       => 'Le prix doit être supérieur à 0.',
            'working_days.required'   => 'Le nombre de jours est obligatoire.',
            'working_days.integer'    => 'Le nombre de jours doit être un entier.',
            'working_days.min'        => 'Le nombre de jours doit être au moins 1.',
            'working_days.max'        => 'Le nombre de jours ne peut pas dépasser 31.',
            'employee_ids.required'   => 'Veuillez sélectionner au moins un employé.',
            'employee_ids.min'        => 'Veuillez sélectionner au moins un employé.',
        ]);

        $brut = $request->price_per_day * $request->working_days;
        $rcar = $brut * 0.06;
        $net  = $brut - $rcar;

        // 1) Save the generation
        DB::beginTransaction();
        try {
            $generation = Document::create([
                'start_date'     => $request->start_date,
                'end_date'       => $request->end_date,
                'signature_date' => $request->signature_date,
                'price_per_day'  => $request->price_per_day,
                'working_days'   => $request->working_days,
                'salaire_brut'   => $brut,
                'rcar'           => $rcar,
                'salaire_net'    => $net,
                'employee_count' => count($request->employee_ids),
                'created_by'     => Auth::id(),
            ]);

            $generation->ouvriers()->attach($request->employee_ids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'general' => 'Erreur lors de l\'enregistrement. Veuillez réessayer.',
            ]);
        }

        // 2) Generate the file (one PDF, or a zip of PDF parts for many workers)
        try {
            $path = (new DocumentGenerator())->generate($generation);
            $generation->update(['zip_filename' => basename($path)]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'general' => $e->getMessage() . ' (Session enregistrée #' . $generation->id . ')',
            ]);
        }

        $mime = str_ends_with($path, '.zip') ? 'application/zip' : 'application/pdf';

        $response = response()->download($path, basename($path), ['Content-Type' => $mime])
            ->deleteFileAfterSend(true);

        // Tells the browser (loader) that the download has started
        $token = preg_replace('/[^A-Za-z0-9]/', '', (string) $request->input('dl_token'));
        if ($token !== '') {
            $response->headers->setCookie(cookie('dl_' . $token, '1', 1, '/', null, false, false));
        }

        return $response;
    }
}
