<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kodepos')->nullable();
            $table->binary('foto_blob')->nullable();  // Tambah Customer 1
            $table->string('foto_path')->nullable();  // Tambah Customer 2
            $table->timestamps();
        });

        // Force PostgreSQL pakai tipe bytea untuk foto_blob
        DB::statement('ALTER TABLE customers ALTER COLUMN foto_blob TYPE bytea USING foto_blob::bytea');
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};