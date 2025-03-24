<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSendStatusToVarcharInVouchersTable extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Ubah send_status dari enum ke varchar(255)
            $table->string('send_status', 255)->nullable()->default('not_sent')->change();
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Kembali ke enum jika rollback diperlukan
            $table->enum('send_status', ['Sending', 'Sent'])->nullable()->default(null)->change();
        });
    }
}