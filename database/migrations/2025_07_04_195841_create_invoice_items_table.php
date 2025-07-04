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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inv_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('default_sell_price_id')->constrained('product_prices');

            $table->bigInteger('ss_online_sell_price')->default(0);
            $table->bigInteger('ss_offline_sell_price')->default(0);
            $table->bigInteger('ss_reseller_sell_price')->default(0);
            $table->bigInteger('ss_resto_sell_price')->default(0);
            $table->bigInteger('ss_bottom_sell_price')->default(0);

            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('stok_id')->nullable()->constrained('stok');

            $table->bigInteger('purchase_price');
            $table->foreignId('customer_price_id')->constrained('customer_prices');

            $table->bigInteger('sell_price');
            $table->bigInteger('ss_komisi_sales');
            $table->bigInteger('profit_gross');

            $table->integer('qty');
            $table->bigInteger('total_purchase_price');
            $table->bigInteger('total_sell_price');
            $table->bigInteger('total_komisi_sales');
            $table->bigInteger('total_profit_gross');

            $table->text('note')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('created_by')->constrained('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
