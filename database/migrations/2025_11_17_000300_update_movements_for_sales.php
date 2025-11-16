<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make id_cliente nullable
        DB::statement('ALTER TABLE movements MODIFY id_cliente BIGINT UNSIGNED NULL');

        Schema::table('movements', function (Blueprint $table) {
            $table->foreignId('id_proveedor')
                ->nullable()
                ->after('id_cliente')
                ->constrained('suppliers', 'id_proveedor')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_proveedor');
        });

        $fallbackClientId = DB::table('clients')->min('id_cliente');

        if ($fallbackClientId) {
            DB::table('movements')
                ->whereNull('id_cliente')
                ->update(['id_cliente' => $fallbackClientId]);
        }

        DB::statement('ALTER TABLE movements MODIFY id_cliente BIGINT UNSIGNED NOT NULL');
    }
};
