<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRMedisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_r_medis', function (Blueprint $table) {
            $table->id('id_rmedis');
            $table->string('norm')->unique()->default(DB::raw('(UUID())'));
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->unsignedBigInteger('id_pemeriksaan');
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
        Schema::dropIfExists('tb_r_medis');
    }
}
