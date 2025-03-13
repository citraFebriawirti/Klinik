<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pemeriksaan', function (Blueprint $table) {
            $table->id('id_pemeriksaan');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->unsignedBigInteger('id_dokter');
            $table->text('diagnosa_pemeriksaan');
            $table->text('catatan_pemeriksaan')->nullable();
            $table->timestamp('tanggal_periksa_pemeriksaan')->useCurrent();

            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('tb_pendaftaran')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('tb_dokter')->onDelete('cascade');

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
        Schema::dropIfExists('tb_pemeriksaan');
    }
}
