<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('company_name');
            $table->string('value');
            $table->date('created_date');
            $table->date('expiration_date');
            $table->enum('status', ['Active', 'Redeemed', 'Expired'])->default('Active');
            $table->string('redeemed_by')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->enum('send_status', ['Sending', 'Sent'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};