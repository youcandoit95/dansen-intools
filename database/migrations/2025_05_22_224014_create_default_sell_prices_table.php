<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('default_sell_prices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id', 'fk_default_sell_prices_product_id')
                ->references('id')
                ->on('products')
                ->restrictOnDelete();

            $table->unsignedBigInteger('online_sell_price')->default(0);
            $table->unsignedBigInteger('offline_sell_price')->default(0);
            $table->unsignedBigInteger('reseller_sell_price')->default(0);
            $table->unsignedBigInteger('resto_sell_price')->default(0);
            $table->unsignedBigInteger('bottom_sell_price')->default(0);

            $table->timestamps();
            $table->softDeletes()->index();
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_sell_prices');
    }
};
