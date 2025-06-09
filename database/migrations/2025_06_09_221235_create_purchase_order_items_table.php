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
    Schema::create('purchase_order_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('purchase_order_id')->index('idx_poi_po_id');
        $table->unsignedBigInteger('product_id')->index('idx_poi_product_id');
        $table->integer('qty')->default(0);
        $table->text('catatan')->nullable();
        $table->unsignedBigInteger('created_by')->nullable()->index('idx_poi_created_by');
        $table->unsignedBigInteger('updated_by')->nullable()->index('idx_poi_updated_by');
        $table->unsignedBigInteger('deleted_by')->nullable()->index('idx_poi_deleted_by');
        $table->timestamps();
        $table->softDeletes()->index('idx_poi_deleted_at');
    });

    Schema::table('purchase_order_items', function (Blueprint $table) {
        $table->foreign('purchase_order_id', 'fk_poi_po_id')->references('id')->on('purchase_orders');
        $table->foreign('product_id', 'fk_poi_product_id')->references('id')->on('products');
        $table->foreign('created_by', 'fk_poi_created_by')->references('id')->on('users');
        $table->foreign('updated_by', 'fk_poi_updated_by')->references('id')->on('users');
        $table->foreign('deleted_by', 'fk_poi_deleted_by')->references('id')->on('users');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
