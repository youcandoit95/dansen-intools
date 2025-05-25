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
            $table->unsignedBigInteger('sales_agent_id')->nullable();

            $table->unsignedBigInteger('harga_jual');
            $table->unsignedBigInteger('komisi_sales')->nullable();

            $table->timestamps();
            $table->softDeletes()->index(); // ✅ soft delete + index

            $table->unique(['customer_id', 'product_id'], 'uniq_customer_product_price');

            $table->foreign('customer_id', 'fk_customer_prices_customer')
                  ->references('id')->on('customers')->cascadeOnDelete();

            $table->foreign('product_id', 'fk_customer_prices_product')
                  ->references('id')->on('products')->cascadeOnDelete();

            $table->foreign('sales_agent_id', 'fk_customer_prices_sales_agent')
                  ->references('id')->on('sales_agents')->nullOnDelete();
        });

        Schema::create('customer_blacklists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->text('alasan');

            $table->timestamps();
            $table->softDeletes()->index(); // ✅ soft delete + index

            $table->foreign('customer_id', 'fk_customer_blacklists_customer')
                  ->references('id')->on('customers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_blacklists');
        Schema::dropIfExists('customer_prices');
    }
};
