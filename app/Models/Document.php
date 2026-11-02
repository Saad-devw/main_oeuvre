<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    protected $table = 'document_generations';

    protected $fillable = [
        'start_date',
        'end_date',
        'price_per_day',
        'working_days',
        'salaire_brut',
        'rcar',
        'salaire_net',
        'employee_count',
        'zip_filename',
        'created_by',
        'signature_date',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'price_per_day' => 'decimal:2',
        'salaire_brut'  => 'decimal:2',
        'rcar'          => 'decimal:2',
        'salaire_net'   => 'decimal:2',
        'signature_date' => 'date',
    ];

    public function ouvriers(): BelongsToMany
    {
        return $this->belongsToMany(
            Ouvrier::class,
            'document_generation_ouvrier',  // pivot table
            'document_generation_id',        // foreign key of THIS model on pivot
            'ouvrier_id'                     // foreign key of Ouvrier on pivot
        );
    }
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
