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
        // Verificar si la tabla ya existe, si es así, la eliminamos para recrearla
        if (Schema::hasTable('vaccination_records')) {
            Schema::drop('vaccination_records');
        }

        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('record_type')->default('vacuna');
            $table->string('vaccine_name')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('document_path')->nullable();
            $table->date('next_date')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('observations')->nullable();
            $table->string('vet_name')->nullable();
            $table->string('location')->nullable();

            // Campos antiguos para compatibilidad
            $table->string('file_path')->nullable();
            $table->date('vaccination_date')->nullable();
            $table->string('vaccine_type')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_records');
    }
};
