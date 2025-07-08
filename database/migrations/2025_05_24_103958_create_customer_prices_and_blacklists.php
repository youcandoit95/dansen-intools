<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_prices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');

            $table->unsignedBigInteger('harga_jual');
            $table->unsignedBigInteger('komisi_sales')->nullable();

            $table->timestamps();
            $table->softDeletes()->index(); // ✅ soft delete + index

            $table->unique(['customer_id', 'product_id'], 'uniq_customer_product_price');

            $table->foreign('customer_id', 'fk_customer_prices_customer')
                  ->references('id')->on('customers')->cascadeOnDelete();

            $table->foreign('product_id', 'fk_customer_prices_product')
                  ->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_prices');
    }
};
