<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votings', function (Blueprint $table) {
            $table->id('id_vote');
            $table->unsignedBigInteger('id_aspirasi');
            $table->unsignedBigInteger('id_user');
            $table->enum('nilai', ['setuju', 'tidak_setuju']);
            $table->dateTime('created_at')->useCurrent();

            // tidak pakai updated_at
            $table->unique(['id_aspirasi', 'id_user']); // satu akun satu suara

            $table->foreign('id_aspirasi')
                  ->references('id_aspirasi')->on('aspirasis')
                  ->onDelete('cascade');

            $table->foreign('id_user')
                  ->references('id_user')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votings');
    }
};
