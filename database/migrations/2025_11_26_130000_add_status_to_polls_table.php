<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            // enum status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
