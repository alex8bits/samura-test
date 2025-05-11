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
        Schema::create('ozon_postings', function (Blueprint $table) {
            $table->id();
            $table->string('posting_number');
            $table->unsignedBigInteger('order_id');
            $table->string('order_number');
            $table->foreignId('warehouse_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ozon_postings');
    }
};
