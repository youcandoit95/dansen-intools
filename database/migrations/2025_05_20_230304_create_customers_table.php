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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();
            $table->foreignId('sales_agent_id')->nullable()->constrained('sales_agents')->restrictOnDelete();
            $table->string('no_tlp')->nullable();
            $table->foreignId('domisili')->constrained('domisili')->restrictOnDelete();
            $table->text('alamat_lengkap')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();

            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
