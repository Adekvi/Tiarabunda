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
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 40)->index();
            $table->string('badge', 80)->nullable();
            $table->string('judul', 180);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('teks_tombol', 80)->nullable();
            $table->string('url_tombol', 255)->nullable();
            $table->boolean('buka_tab_baru')->default(false);
            $table->string('teks_sekunder', 80)->nullable();
            $table->string('url_sekunder', 255)->nullable();
            $table->unsignedInteger('urutan')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
