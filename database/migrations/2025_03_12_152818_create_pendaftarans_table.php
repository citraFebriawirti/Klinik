<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_poli');
            $table->enum('status_pendaftaran', ['Menunggu', 'Selesai'])->default('Menunggu');
            $table->timestamp('tanggal_daftar_pendaftaran')->useCurrent();

            $table->foreign('id_pasien')->references('id_pasien')->on('tb_pasien')->onDelete('cascade');
            $table->foreign('id_poli')->references('id_poli')->on('tb_poli')->onDelete('cascade');

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
        Schema::dropIfExists('tb_pendaftaran');
    }
}
