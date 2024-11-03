<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarToMBarangTable extends Migration
{
    public function up()
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('barang_nama'); // Tambahkan kolom avatar
        });
    }

    public function down()
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->dropColumn('avatar'); // Hapus kolom avatar jika migrasi dibatalkan
        });
    }
}