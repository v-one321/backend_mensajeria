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
        Schema::create('detalle_chat_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chat_models')->onDelete('cascade')->onUpdate('cascade');
            $table->text('mensaje')->nullable();
            $table->string('archivo_adjunto', 50)->nullable();
            $table->string('tipo', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_chat_models');
    }
};
