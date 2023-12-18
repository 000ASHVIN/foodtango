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
        Schema::create('payments_to_restaurants', function (Blueprint $table) {
            $table->id();

            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('orders')->nullable();
            $table->integer('total_payment')->default(0)->nullable();
            $table->integer('total_order_payment')->default(0)->nullable();
            $table->integer('total_service_fees')->default(0)->nullable();
            $table->string('payment_by')->nullable();
            $table->string('method')->nullable();
            $table->string('reference')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_to_restaurants');
    }
};
