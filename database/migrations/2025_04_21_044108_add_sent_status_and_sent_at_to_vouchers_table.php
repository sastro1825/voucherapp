<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('sent_status')->nullable()->after('sent_to'); // Kolom untuk status pengiriman
            $table->timestamp('sent_at')->nullable()->after('sent_status'); // Kolom untuk waktu pengiriman
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['sent_status', 'sent_at']);
        });
    }
};