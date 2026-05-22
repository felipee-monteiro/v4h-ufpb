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
        Schema::table('teleconsultorias', function (Blueprint $table): void {
            $table->text('professional_opinion')->nullable()->after('clinical_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teleconsultorias', function (Blueprint $table): void {
            $table->dropColumn('professional_opinion');
        });
    }
};
