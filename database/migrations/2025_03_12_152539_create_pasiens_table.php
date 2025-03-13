<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pasien', function (Blueprint $table) {
            $table->id('id_pasien');
            $table->string('nama_pasien', 255);
            $table->string('nik_pasien', 16)->unique();
            $table->date('tanggallahir_pasien');
            $table->enum('jenis_kelamin_pasien', ['L', 'P']);
            $table->text('alamat_pasien')->nullable();
            $table->string('no_hp_pasien', 15)->nullable();
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
        Schema::dropIfExists('tb_pasien');
    }
}
