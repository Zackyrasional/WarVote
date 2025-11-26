<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('user_id'); // id_user dari tabel users
            $table->timestamps();

            $table->index(['poll_id', 'user_id']);
            $table->index('option_id');

            // Jika ingin batasi 1 user 1 vote per polling, nanti bisa buat unique:
            // $table->unique(['poll_id', 'user_id', 'option_id']);
            //
            // Foreign key kalau mau:
            // $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            // $table->foreign('option_id')->references('id')->on('poll_options')->onDelete('cascade');
            // $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
    }
};
