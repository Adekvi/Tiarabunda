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
        Schema::create('kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 30)->default('mitra')->nullable();
            $table->string('tandha')->unique();
            $table->string('kategori', 100)->default('Industri & Perusahaan')->nullable();
            $table->string('nomor_dokumen', 120)->nullable();
            $table->string('periode', 100)->nullable();
            $table->text('katrangan')->nullable();
            $table->string('logo')->nullable();
            $table->string('path_file')->nullable();
            $table->string('nama_file_asli')->nullable();
            $table->string('tautan')->nullable();
            $table->string('kontak_pic', 150)->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['urip', 'deleted_at', 'jenis', 'urutan'], 'idx_kerjasamas_listing');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasamas');
    }
};
