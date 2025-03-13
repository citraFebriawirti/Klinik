<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_resep_detail', function (Blueprint $table) {
            $table->id('id_resep_detail');
            $table->unsignedBigInteger('id_resep');
            $table->unsignedBigInteger('id_obat');
            $table->string('dosis_resep_detail', 50);
            $table->integer('jumlah_resep_detail');
            $table->text('aturan_pakai_resep_detail');
            $table->timestamps();

            $table->foreign('id_resep')->references('id_resep')->on('tb_resep')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('tb_obat')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_resep_detail');
    }
}
