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
        Schema::create('mbs', function (Blueprint $table) {
            $table->id();
            $table->string('a_grade', 2); // contoh: A1, A2, dst
            $table->unsignedTinyInteger('bms')->unique(); // 0 - 12
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbs');
    }
};
