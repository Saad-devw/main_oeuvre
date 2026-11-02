<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_generations', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price_per_day', 8, 2);
            $table->decimal('salaire_brut', 10, 2);
            $table->decimal('rcar', 10, 2);
            $table->decimal('salaire_net', 10, 2);
            $table->integer('employee_count');
            $table->string('zip_filename')->nullable(); // the generated zip file name
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('document_generation_ouvrier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_generation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ouvrier_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_generation_ouvrier');
        Schema::dropIfExists('document_generations');
    }
};
