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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('original_company_id')->nullable()->constrained('original_shipping_companies')->onDelete('cascade');
            $table->string('number')->unique();
            $table->date('shipping_date');
            $table->string('service_type');
            $table->string('origin_country')->nullable();
            $table->string('destination_country');
            $table->string('shipping_method')->nullable();
            $table->integer('cargo_weight');
            $table->integer('containers_size')->nullable();
            $table->integer('containers_numbers')->nullable();
            $table->text('employee_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->boolean('is_information_complete')->default(0);
            $table->boolean('is_confirm')->default(0);
            $table->boolean('having_supplier')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
