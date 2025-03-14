<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXPengambilanObatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengambilan_obat', function (Blueprint $table) {
            $table->id('id_pengambilan');
            $table->unsignedBigInteger('id_resep');
            $table->enum('status_pengambilan_obat', ['Diambil', 'Belum Diambil'])->default('Belum Diambil');
            $table->timestamp('tanggal_ambil_pengambilan_obat')->nullable();
            $table->timestamps();

            $table->foreign('id_resep')->references('id_resep')->on('tb_resep')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengambilan_obat');
    }
}
