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
        Schema::create('ozon_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('offer_id');
            $table->unsignedBigInteger('sku');
            $table->string('name');
            $table->string('price');
            $table->unsignedInteger('hits_view')->nullable();
            $table->unsignedInteger('hits_view_pdp')->nullable();
            $table->unsignedInteger('hits_tocart')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_products');
    }
};
