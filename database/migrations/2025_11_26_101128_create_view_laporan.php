<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Pastikan view lama dihapus dulu kalau ada
        DB::statement("DROP VIEW IF EXISTS view_laporan");

        DB::statement("
            CREATE VIEW view_laporan AS
            SELECT 
                a.id_aspirasi,
                a.judul,
                SUM(CASE WHEN v.nilai = 'setuju' THEN 1 ELSE 0 END) AS total_setuju,
                SUM(CASE WHEN v.nilai = 'tidak_setuju' THEN 1 ELSE 0 END) AS total_tidak_setuju,
                COUNT(v.id_vote) AS total_suara
            FROM aspirasis a
            LEFT JOIN votings v ON v.id_aspirasi = a.id_aspirasi
            GROUP BY a.id_aspirasi, a.judul
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_laporan");
    }
};
