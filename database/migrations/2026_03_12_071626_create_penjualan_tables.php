<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Provinsi
        Schema::create('provinsi', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('nama', 100);
        });

        // Tabel Kota/Kabupaten
        Schema::create('kota', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('provinsi_id');
            $table->string('nama', 100);
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        });

        // Tabel Kecamatan
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('kota_id');
            $table->string('nama', 100);
            $table->foreign('kota_id')->references('id')->on('kota')->onDelete('cascade');
        });

        // Tabel Kelurahan
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->integer('kecamatan_id');
            $table->string('nama', 100);
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
        });

        // Tabel Penjualan
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->integer('total');
            $table->timestamp('timestamp')->useCurrent();
        });

        // Tabel Penjualan Detail
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('idpenjualan_detail');
            $table->unsignedBigInteger('id_penjualan');
            $table->string('id_barang', 8);
            $table->smallInteger('jumlah');
            $table->integer('subtotal');

            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('kelurahan');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kota');
        Schema::dropIfExists('provinsi');
    }
};
