<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('merchant_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('used_balance', 15, 2)->default(0); // Total nilai voucher yang digunakan
            $table->decimal('remaining_balance', 15, 2)->default(300000); // Sisa saldo
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['merchant_id', 'year', 'month']); // Pastikan hanya satu entri per merchant per bulan
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchant_balances');
    }
}