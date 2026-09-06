<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teleconsultorias', function (Blueprint $table) {
            // Composto com created_at: além de filtrar por solicitante_uuid,
            // já entrega as linhas na ordem que a query pede (ORDER BY
            // created_at DESC), então o LIMIT 15 é satisfeito lendo só as
            // primeiras 15 entradas do índice — sem sort de milhões de linhas.
            DB::statement(<<<'SQL'
            CREATE INDEX CONCURRENTLY IF NOT EXISTS teleconsultorias_solicitante_created_at_index
                ON teleconsultorias (solicitante_uuid, created_at DESC)
        SQL);

            // Mesma lógica para o branch de "sou o especialista do service".
            DB::statement(<<<'SQL'
            CREATE INDEX CONCURRENTLY IF NOT EXISTS teleconsultorias_service_created_at_index
                ON teleconsultorias (service_uuid, created_at DESC)
        SQL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teleconsultorias', function (Blueprint $table) {
            DB::statement('DROP INDEX CONCURRENTLY IF EXISTS teleconsultorias_solicitante_created_at_index');
            DB::statement('DROP INDEX CONCURRENTLY IF EXISTS teleconsultorias_service_created_at_index');
        });
    }
};
