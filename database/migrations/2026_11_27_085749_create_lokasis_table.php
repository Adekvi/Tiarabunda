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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->nullable();
            $table->string('badge')->nullable();
            $table->string('nama')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jam')->nullable();
            $table->string('telepon', 50)->nullable();
            $table->string('koordinat', 100)->nullable();
            $table->string('akses')->nullable();
            $table->json('unggulan')->nullable();
            $table->string('foto')->nullable();
            $table->text('map_embed')->nullable();
            $table->string('map_url')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
