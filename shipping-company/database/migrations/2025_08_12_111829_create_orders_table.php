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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')
                ->constrained('shipments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('vendor_id')
                ->constrained('vendor_shops')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->geometry('deliver_location');
            $table->string('order_status')->default('pending');
            $table->string('size');
            $table->string('weight');
            $table->string('width');
            $table->string('height');
            $table->string('length');
            $table->string('price');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
