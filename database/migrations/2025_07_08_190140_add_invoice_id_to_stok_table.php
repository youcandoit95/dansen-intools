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
        Schema::table('stok', function (Blueprint $table) {
    $table->foreignId('invoice_id')
        ->nullable()
        ->after('inbound_id')
        ->constrained('invoices')
        ->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok', function (Blueprint $table) {
    $table->dropForeign(['invoice_id']);
    $table->dropColumn('invoice_id');
});

    }
};
