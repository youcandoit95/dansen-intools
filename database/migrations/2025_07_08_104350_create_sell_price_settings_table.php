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
        Schema::create('sell_price_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('online');
            $table->unsignedTinyInteger('offline');
            $table->unsignedTinyInteger('reseller');
            $table->unsignedTinyInteger('resto');
            $table->unsignedTinyInteger('bottom');

            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();

            $table->softDeletes(); // <-- ini soft delete Laravel

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_price_settings');
    }
};
