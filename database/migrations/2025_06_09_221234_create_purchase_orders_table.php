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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_po')->unique();
            $table->unsignedBigInteger('supplier_id')->index('idx_po_supplier');
            $table->date('tanggal');
            $table->date('tanggal_permintaan_dikirim')->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('idx_po_created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->index('idx_po_updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable()->index('idx_po_deleted_by');
            $table->timestamps();
            $table->softDeletes()->index('idx_po_deleted_at');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreign('supplier_id', 'fk_po_supplier')->references('id')->on('suppliers');
            $table->foreign('created_by', 'fk_po_created_by')->references('id')->on('users');
            $table->foreign('updated_by', 'fk_po_updated_by')->references('id')->on('users');
            $table->foreign('deleted_by', 'fk_po_deleted_by')->references('id')->on('users');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
