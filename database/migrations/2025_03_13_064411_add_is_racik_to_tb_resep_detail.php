<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRacikToTbResepDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_resep_detail', function (Blueprint $table) {
            $table->boolean('is_racik')->default(false)->after('id_obat');
            $table->string('nama_racik')->nullable()->after('is_racik'); // Nama resep racik, misal RACK-01
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_resep_detail', function (Blueprint $table) {
            $table->dropColumn('is_racik');
            $table->dropColumn('nama_racik');
        });
    }
}
