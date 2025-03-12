<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddGenreToBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->unsignedBigInteger('genre_buku')->nullable()->after('id');

            // Tambahkan foreign key
            $table->foreign('genre_buku')->references('id')->on('genres')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['genre_buku']);
            $table->dropColumn('genre_buku');
        });
    }
}
