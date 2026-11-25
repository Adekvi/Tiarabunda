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
        Schema::create('lpms', function (Blueprint $table) {
            $table->id();
            $table->string('judhul')->nullable();
            $table->string('tandha')->nullable()->unique();
            $table->string('kategori', 50)->default('spmi')->nullable();
            $table->longText('isi')->nullable();
            $table->json('data_tambahan')->nullable();
            $table->string('path_file')->nullable();
            $table->string('nama_file_asli')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['urip', 'deleted_at', 'kategori', 'urutan'], 'idx_lpms_listing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpms');
    }
};
