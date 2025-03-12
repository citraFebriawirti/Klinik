<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_dokter', function (Blueprint $table) {
            $table->id('id_dokter');
            $table->string('nama_dokter', 255);
            $table->unsignedBigInteger('id_poli');
            $table->string('spesialisasi_dokter', 100);
            $table->string('no_hp_dokter', 15)->nullable();

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
        Schema::dropIfExists('tb_dokter');
    }
}
