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
        Schema::create('pmbs', function (Blueprint $table) {
            $table->id();
            $table->string('jeneng')->nullable();
            $table->string('tandha')->unique();
            $table->text('katrangan')->nullable();
            $table->json('syarat')->nullable();
            $table->json('langkah')->nullable();
            $table->date('wektu_bukak')->nullable();
            $table->date('wektu_tutup')->nullable();
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
        Schema::dropIfExists('pmbs');
    }
};
