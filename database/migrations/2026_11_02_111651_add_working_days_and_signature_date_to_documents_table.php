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
        Schema::table('document_generations', function (Blueprint $table) {
            $table->unsignedSmallInteger('working_days')->default(22)->after('price_per_day');
            $table->date('signature_date')->nullable()->after('working_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_generations', function (Blueprint $table) {
            $table->dropColumn('working_days');
            $table->dropColumn('signature_date');
        });
    }
};

