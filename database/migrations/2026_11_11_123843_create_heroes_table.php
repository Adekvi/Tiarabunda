<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('sub_judul')->nullable();
            $table->string('teks_tombol_1')->default('Daftar sekarang');
            $table->string('link_tombol_1')->default('#pmb');
            $table->string('teks_tombol_2')->default('Lihat program studi');
            $table->string('link_tombol_2')->default('#prodi');

            // Media (Video atau Gambar)
            $table->enum('tipe_media', ['gambar', 'video'])->default('gambar');
            $table->string('path_media')->nullable();
            $table->string('path_poster')->nullable();

            $table->boolean('urip')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
