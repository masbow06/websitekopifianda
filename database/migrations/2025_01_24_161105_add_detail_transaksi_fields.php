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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->char('namapemesan')->after('paymentstat');
            $table->char('phone')->after('namapemesan');
            $table->char('alamat')->after('phone');
            $table->char('email')->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('transaksis', ['namapemesan']);
        Schema::dropColumns('transaksis', ['phone']);
        Schema::dropColumns('transaksis', ['alamat']);
        Schema::dropColumns('transaksis', ['email']);
    }
};
