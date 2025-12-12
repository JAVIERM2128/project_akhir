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
        Schema::table('topups', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable(); // Tanggal persetujuan
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang menyetujui
            $table->text('rejection_reason')->nullable(); // Alasan penolakan jika ditolak
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topups', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'approved_by', 'rejection_reason']);
        });
    }
};
