<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_id')->after('id')->index('idx_po_cabang_id');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreign('cabang_id', 'fk_po_cabang_id')
                ->references('id')->on('cabang');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign('fk_po_cabang_id');
            $table->dropIndex('idx_po_cabang_id');
            $table->dropColumn('cabang_id');
        });
    }
};
