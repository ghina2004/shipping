<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('order_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->integer('tracking_number');
            $table->string('tracking_link');
            $table->integer('status');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shipment_routes');
    }
};
