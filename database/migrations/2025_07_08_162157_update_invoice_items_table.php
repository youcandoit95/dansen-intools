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
        Schema::table('invoice_items', function (Blueprint $table) {
            // Rename kolom qty ke qty_outbound
            $table->renameColumn('qty', 'qty_outbound');

            // Tambah kolom waste_kg dan waste_amount
            $table->decimal('waste_kg', 10, 3)->after('qty_outbound')->default(0);
            $table->bigInteger('waste_amount')->after('waste_kg')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('invoice_items', function (Blueprint $table) {
            // Rollback kolom tambahan
            $table->dropColumn(['waste_kg', 'waste_amount']);

            // Rename kembali qty_outbound ke qty
            $table->renameColumn('qty_outbound', 'qty');
        });
    }
};
