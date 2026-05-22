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
        Schema::create('professional_service', function (Blueprint $table) {
            $table->foreignUuid('professional_uuid')->constrained('users', 'uuid')->cascadeOnDelete();
            $table->foreignUuid('service_uuid')->constrained('services', 'uuid')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['professional_uuid', 'service_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_service');
    }
};
