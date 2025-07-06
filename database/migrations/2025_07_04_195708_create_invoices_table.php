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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no')->unique();
            $table->foreignId('customer_id')->constrained('customers');

            $table->bigInteger('g_total_purchase_price')->nullable();
            $table->integer('g_total_qty')->nullable()->index();
            $table->bigInteger('g_total_sell_price')->nullable()->index();
            $table->bigInteger('g_total_komisi_sales')->nullable()->index();

            $table->integer('discount_pcnt')->nullable();
            $table->bigInteger('discount_amount')->nullable()->index();

            $table->bigInteger('g_total_profit_gross')->nullable();
            $table->bigInteger('packaging_fee')->nullable();
            $table->bigInteger('additional_fee')->nullable();
            $table->text('additional_fee_note')->nullable();

            $table->bigInteger('g_total_invoice_amount')->nullable()->index();
            $table->text('note')->nullable();

            $table->tinyInteger('platform_id')->nullable()->index()->comment('1=Tokopedia, 2=TiktokShop, 3=Shopee, 4=Blibli, 5=Toco');
            $table->bigInteger('platform_paid_amount')->nullable();

            $table->timestamp('lunas_at')->nullable()->index();
            $table->foreignId('lunas_by')->nullable()->constrained('users');

            $table->timestamp('checked_finance_at')->nullable()->index();
            $table->foreignId('checked_finance_by')->nullable()->constrained('users');

            $table->boolean('cancel')->default(false)->index();
            $table->text('cancel_reason')->nullable();

            $table->timestamp('komisi_paid_at')->nullable()->index();
            $table->foreignId('komisi_paid_by')->nullable()->constrained('users');
            $table->string('komisi_paid_proof_doc')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('cancel_by')->nullable()->constrained('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
