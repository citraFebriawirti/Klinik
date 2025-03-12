<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJanjiTemusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('janji_temu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nik_pasien')->constrained('pasien', 'nik_pasien')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kode_dokter')->constrained('dokter', 'kode_dokter')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggalwaktu_janji_temu');
            $table->string('keluhan_janji_temu');
            $table->enum('status_janji_temu', ['Pending', 'Dikonfirmasi', 'Selesai', 'Batal']);
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
        Schema::dropIfExists('janji_temu');
    }
}
