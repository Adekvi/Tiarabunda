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
        Schema::create('sejarahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dianyari_dening')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judhul')->nullable();
            $table->longText('isi')->nullable();
            $table->json('kronologi')->nullable();
            $table->string('path_sampul')->nullable();
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
        Schema::dropIfExists('sejarahs');
    }
};
