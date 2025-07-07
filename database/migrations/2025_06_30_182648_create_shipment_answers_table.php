<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('shipment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');;
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');;
            $table->foreignId('shipment_question_id')->constrained('shipment_questions')->onDelete('cascade');;
            $table->string('answer');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shipment_answers');
    }
};
