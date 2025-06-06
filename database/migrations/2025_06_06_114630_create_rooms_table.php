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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama ruangan (contoh: Ruang Rapat A)
            $table->string('location')->nullable(); // Lokasi ruangan (contoh: Lantai 3)
            $table->integer('capacity'); // Kapasitas ruangan
            $table->text('description')->nullable(); // Deskripsi ruangan
            $table->string('image')->nullable(); // Path gambar ruangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};