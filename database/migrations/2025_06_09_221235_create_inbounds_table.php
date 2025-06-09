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
    Schema::create('inbounds', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('purchase_order_id')->nullable()->index('idx_ib_po_id');
        $table->unsignedBigInteger('product_id')->index('idx_ib_product_id');
        $table->enum('tipe', ['1', '2', '3', '4']);
        $table->decimal('berat', 8, 3)->nullable();
        $table->integer('qty')->nullable();
        $table->string('qr_code')->nullable();
        $table->text('catatan')->nullable();
        $table->boolean('destroy')->default(false);
        $table->text('catatan_destroy')->nullable();
        $table->timestamp('destroyed_at')->nullable();
        $table->unsignedBigInteger('destroyed_by')->nullable()->index('idx_ib_destroyed_by');
        $table->timestamps();
    });

    Schema::table('inbounds', function (Blueprint $table) {
        $table->foreign('purchase_order_id', 'fk_ib_po_id')->references('id')->on('purchase_orders');
        $table->foreign('product_id', 'fk_ib_product_id')->references('id')->on('products');
        $table->foreign('destroyed_by', 'fk_ib_destroyed_by')->references('id')->on('users');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbounds');
    }
};
