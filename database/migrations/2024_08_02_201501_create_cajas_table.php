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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nro_respaldo');
            $table->decimal('importe', 10,2)->default(0);
            $table->string('tipo_movimiento', 10);
            $table->text('detalle')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
