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
        Schema::create('vendor_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('shop_name');
            $table->geometry('location');
            $table->string('shop_phone')->nullable();
            $table->string('shop_email');
            $table->string('shop_website')->nullable();
            $table->string('shop_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendore_shops');
    }
};
