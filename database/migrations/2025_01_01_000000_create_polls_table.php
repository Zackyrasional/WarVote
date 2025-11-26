<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');              // Tujuan polling
            $table->boolean('allow_multiple')->default(false); // Izinkan beberapa pilihan
            $table->dateTime('deadline')->nullable();          // Batas waktu vote
            $table->unsignedBigInteger('created_by');          // id_user pembuat
            $table->timestamps();

            // kalau mau foreign key, bisa begini (id_user pada tabel users):
            // $table->foreign('created_by')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
