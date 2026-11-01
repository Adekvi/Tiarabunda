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
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('jeneng')->nullable();
            $table->string('tandha')->unique()->nullable();
            $table->text('katrangan')->nullable();
            $table->text('syarat')->nullable();
            $table->string('jangkepan')->nullable();
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
        Schema::dropIfExists('beasiswas');
    }
};
