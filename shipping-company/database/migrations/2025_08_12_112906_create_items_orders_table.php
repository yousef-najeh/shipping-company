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
        Schema::create('items_orders', function (Blueprint $table) {
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->id();
            $table->integer('quantity')->default(1);
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_orders');
    }
};
