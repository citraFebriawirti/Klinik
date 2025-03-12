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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nik_pasien')->unique();

            $table->string('nama_pasien');
            $table->string('tempatlahir_pasien');
            $table->date('tanggallahir_pasien'); // Format tanggal
            $table->enum('jeniskelamin_pasien', ['Laki-laki', 'Perempuan']); // Pilihan tetap
            $table->text('alamat_pasien'); // Bisa lebih panjang
            $table->string('nomorhp_pasien', 15); // Maksimal 15 karakter untuk nomor HP
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
        Schema::dropIfExists('pasien');
    }
}
