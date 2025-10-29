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
        Schema::create('connector_types', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->timestamps();
        });

        Schema::create('connectors', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->decimal('price');
            $table->text('description');

            $table->foreignId('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
            $table->foreignId('connector_type_id')->references('id')->on('connector_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('connectors');
        Schema::dropIfExists('connector_types');
    }
};
