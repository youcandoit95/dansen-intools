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
       Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_id')->nullable()->after('id');

            // Buat nama FK unik, misalnya: fk_invoices_cabang_id
            $table->foreign('cabang_id', 'fk_invoices_cabang_id')
                  ->references('id')->on('cabang')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn('cabang_id');
        });
    }
};
