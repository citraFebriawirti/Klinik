<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transkasi');
            $table->unsignedBigInteger('id_resep');
            $table->decimal('total_bayar_transaksi', 15, 2);
            $table->enum('status_transaksi', ['Lunas', 'Belum Lunas']);
            $table->date('tanggal_bayar_transaksi');
            $table->foreign('id_resep')->references('id_resep')->on('tb_resep')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
