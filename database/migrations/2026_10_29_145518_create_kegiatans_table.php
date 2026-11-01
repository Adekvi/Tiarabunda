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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('judhul')->nullable();
            $table->string('tandha')->unique()->nullable();
            $table->text('katrangan')->nullable();
            $table->string('papan')->nullable();
            $table->timestamp('wektu_mulai')->nullable();
            $table->timestamp('wektu_rampung')->nullable();
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
        Schema::dropIfExists('kegiatans');
    }
};
