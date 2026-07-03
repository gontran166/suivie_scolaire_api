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
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->string('titre');

            $table->text('contenu');

            $table->enum('type', [
                'annonce',
                'examen',
                'reunion',
                'paiement'
            ])->default('annonce');

            $table->foreignId('classe_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('active')->default(true);
            $table->timestamp('date_expiration')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
