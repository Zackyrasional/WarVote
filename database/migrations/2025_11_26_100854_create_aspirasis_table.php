<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id('id_aspirasi');
            $table->unsignedBigInteger('id_user');
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->enum('status', ['submitted', 'approved', 'rejected'])
                  ->default('submitted');
            $table->dateTime('tanggal_post')->useCurrent();
            $table->timestamps();

            $table->foreign('id_user')
                  ->references('id_user')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};
