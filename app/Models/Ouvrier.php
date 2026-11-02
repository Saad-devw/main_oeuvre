<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ouvrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'cin',
        'his_work',
        'gender',
        'personal_pdf',
    ];
}
