<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_resep', function (Blueprint $table) {
            $table->id('id_resep');
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->enum('status_resep', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->decimal('total_harga_resep', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('tb_pemeriksaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_resep');
    }
}
