<?php

namespace App\Http\Controllers;

use App\Models\Document;         // ← add this
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
        $validated = $request->validate([
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after_or_equal:start_date'],
            'price_per_day'  => ['required', 'numeric', 'min:1'],
            'employee_ids'   => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:ouvriers,id'],
            'working_days'   => ['required', 'integer', 'min:1', 'max:31'],
            'signature_date' => ['nullable', 'date'],
        ], [
            'start_date.required'     => 'La date de début est obligatoire.',
            'end_date.required'       => 'La date de fin est obligatoire.',
            'end_date.after_or_equal' => 'La date de fin doit être après la date de début.',
            'price_per_day.required'  => 'Le prix par jour est obligatoire.',
            'price_per_day.min'       => 'Le prix doit être supérieur à 0.',
            'employee_ids.required'   => 'Veuillez sélectionner au moins un employé.',
            'employee_ids.min'        => 'Veuillez sélectionner au moins un employé.',
            'working_days.required' => 'Le nombre de jours est obligatoire.',
        ]);

        $brut = $request->price_per_day * $request->working_days;
        $rcar = $brut * 0.06;
        $net  = $brut - $rcar;

        DB::beginTransaction();
        try {
            $generation = Document::create([
                'start_date'     => $request->start_date,
                'end_date'       => $request->end_date,
                'price_per_day'  => $request->price_per_day,
                'working_days'   => $request->working_days,
                'signature_date' => $request->signature_date,
                'salaire_brut'   => $brut,
                'rcar'           => $rcar,
                'salaire_net'    => $net,
                'employee_count' => count($request->employee_ids),
                'created_by'     => Auth::user()->id,
            ]);



            $generation->ouvriers()->attach($request->employee_ids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'general' => 'Erreur lors de l\'enregistrement. Veuillez réessayer.',
            ]);
        }

        try {
            $pdfPath = (new DocumentGenerator())->generatePdf($generation);
            $generation->update(['zip_filename' => basename($pdfPath)]);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => $e->getMessage() . ' Session enregistrée (#' . $generation->id . ').']);
        }

        return response()->download($pdfPath, basename($pdfPath), ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }
}
