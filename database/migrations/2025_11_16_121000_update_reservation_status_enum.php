<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE reservation_headers MODIFY estado ENUM('Pendiente','Retirado','Reservado','Parcial','Completado','Cancelado') NOT NULL DEFAULT 'Pendiente'");

        DB::statement("UPDATE reservation_headers SET estado = 'Completado' WHERE estado = 'Retirado'");
        DB::statement("UPDATE reservation_headers SET estado = 'Reservado' WHERE estado = 'Pendiente'");

        DB::statement("ALTER TABLE reservation_headers MODIFY estado ENUM('Reservado','Parcial','Completado','Cancelado') NOT NULL DEFAULT 'Reservado'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reservation_headers MODIFY estado ENUM('Reservado','Parcial','Completado','Cancelado','Pendiente','Retirado') NOT NULL DEFAULT 'Reservado'");

        DB::statement("UPDATE reservation_headers SET estado = 'Retirado' WHERE estado = 'Completado'");
        DB::statement("UPDATE reservation_headers SET estado = 'Pendiente' WHERE estado IN ('Reservado','Parcial')");

        DB::statement("ALTER TABLE reservation_headers MODIFY estado ENUM('Pendiente','Retirado','Cancelado') NOT NULL DEFAULT 'Pendiente'");
    }
};
