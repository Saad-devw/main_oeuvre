<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OuvrierController;
use App\Http\Controllers\ProfileController;
use App\Models\Ouvrier;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'totalOuvriers'  => Ouvrier::count(),
        'totalHommes'    => Ouvrier::where('gender', 'homme')->count(),
        'totalFemmes'    => Ouvrier::where('gender', 'femme')->count(),
        // 'totalDocuments' => Documents::count(), // or however you track this
        'recentOuvriers' => Ouvrier::latest()->take(6)->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('ouvriers/import', [OuvrierController::class, 'import'])->name('ouvriers.import');

    Route::post('ouvriers/bulk-pdf', [OuvrierController::class, 'bulkPdf'])->name('ouvriers.bulk-pdf');
    
    Route::resource('ouvriers', OuvrierController::class);

    // Employees CRUD
    Route::resource('ouvriers', OuvrierController::class)->except(['show']);


    // Document generation
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents/generate', [DocumentController::class, 'generate'])->name('documents.generate');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
