<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_obat', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('nama_obat', 50);
            $table->string('jenis_obat', 50);
            $table->string('satuan_obat', 50);
            $table->integer('stok_obat');
            $table->integer('harga_obat');
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
        Schema::dropIfExists('tb_obat');
    }
}
