<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->string('type', 50);
            $table->string('title')->nullable();
            $table->string('unsigned_file_path')->nullable();
            $table->string('signed_file_path')->nullable();
            $table->string('status', 50)->default('pending_signature');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->boolean('show_signed_to_customer')->default(false);
            $table->boolean('show_unsigned_to_customer')->default(false);
            $table->timestamps();
            $table->index(['shipment_id', 'type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
