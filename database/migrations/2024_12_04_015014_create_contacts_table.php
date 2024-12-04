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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->integer('age')->nullable();
            
            // Definir user_id como chave estrangeira
            $table->foreignId('user_id')
                ->constrained() // Conecta-se à tabela `users` automaticamente
                ->onDelete('cascade') // Exclui os contatos se o usuário for deletado
                ->onUpdate('cascade'); // Atualiza se o `id` do usuário mudar
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
