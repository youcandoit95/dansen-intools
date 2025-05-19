<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->tinyInteger('kategori'); // 1 = loaf/kg, 2 = cut/kg, 3 = pcs/pack
            $table->tinyInteger('brand');    // 1 = Tokusen, 2 = Sher Wagyu, 3 = Angus Pure/G
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('status')->default(true); // true = aktif
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
