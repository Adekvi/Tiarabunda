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
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->nullable();
            $table->string('peran', 180)->nullable();
            $table->string('angkatan', 80)->nullable();
            $table->string('kategori', 30)->default('alumni')->nullable();
            $table->string('instansi', 180)->nullable();
            $table->string('sorotan', 200)->nullable();
            $table->text('kutipan')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['urip', 'urutan']);
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonis');
    }
};
