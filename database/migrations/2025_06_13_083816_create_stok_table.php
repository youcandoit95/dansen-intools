<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('kategori'); // 1=loaf/kg, 2=cut/kg, 3=pcs/pack, 99=waste
            $table->decimal('berat_kg', 10, 3);
            $table->timestamp('destroy_at')->nullable()->index();
            $table->unsignedBigInteger('destroy_by')->nullable();
            $table->text('destroy_reason')->nullable();
            $table->string('destroy_foto')->nullable();
            $table->timestamp('destroy_approved_at')->nullable()->index();
            $table->unsignedBigInteger('destroy_approved_by')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('destroy_by')->references('id')->on('users');
            $table->foreign('destroy_approved_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index('created_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('stok');
    }
};
