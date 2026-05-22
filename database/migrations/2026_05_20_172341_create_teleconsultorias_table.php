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
        Schema::create('teleconsultorias', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignUuid('solicitante_uuid')->constrained('users', 'uuid')->cascadeOnDelete();

            $table->foreignUuid('service_uuid')->constrained('services', 'uuid')->cascadeOnDelete();

            $table->string('patient_name');

            $table->string('patient_initials', 8);

            $table->date('patient_birthday');

            $table->text('diagnostic_hypothesis');

            $table->text('clinical_history');

            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teleconsultorias');
    }
};
