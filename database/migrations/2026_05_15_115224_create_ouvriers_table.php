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
        Schema::create('ouvriers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('cin', 20)->unique();
            $table->string('his_work');
            $table->enum('gender', ['homme', 'femme'])->default('homme');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ouvriers');
    }
};
