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
        Schema::create('ozon_posting_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posting_id')->constrained('ozon_postings', 'id')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('ozon_products', 'id')->cascadeOnDelete();
            $table->string('price');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_posting_items');
    }
};
