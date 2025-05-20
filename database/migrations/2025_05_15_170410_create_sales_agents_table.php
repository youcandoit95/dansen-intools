<?php

// database/migrations/xxxx_xx_xx_create_sales_agents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesAgentsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_agents', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telepon')->index(); // Wajib dan indexed
            $table->string('email')->nullable()->index(); // Nullable dan indexed
            $table->foreignId('domisili')->constrained('domisili')->index(); // Relasi ke table `domisili`
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_agents');
    }
}
