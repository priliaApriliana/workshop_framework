<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('nama_vendor', 100);
            $table->unsignedBigInteger('id_user')->nullable(); // relasi ke tabel users
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });

        // Tabel Menu
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->unsignedBigInteger('id_vendor');
            $table->string('nama_menu', 100);
            $table->integer('harga');
            $table->string('gambar', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
        });

        // Tabel Pesanan (Order)
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('nama_customer', 255);
            $table->integer('total')->default(0);
            $table->integer('metode_bayar')->default(0); // 0=belum dipilih, 1=VA, 2=QRIS
            $table->smallInteger('status_bayar')->default(0); // 0=pending, 1=lunas
            $table->timestamps();
        });

        // Tabel Detail Pesanan
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail_pesanan');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_menu');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('catatan', 255)->nullable();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
        });

        // Tabel Payment (untuk menyimpan informasi pembayaran dari payment gateway)
        Schema::create('payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->unsignedBigInteger('id_pesanan');
            $table->string('payment_method', 50); // virtual_account, qris
            $table->string('payment_reference', 255);
            $table->integer('amount');
            $table->string('status', 50)->default('pending'); // pending, completed, failed
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('vendor');
    }
};
