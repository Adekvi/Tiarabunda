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
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digawe_dening')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judhul')->nullable();
            $table->string('tandha')->unique()->nullable();
            $table->string('golongan', 50)->nullable();
            $table->string('ringkesan', 300)->nullable();
            $table->longText('isi')->nullable();
            $table->string('path_sampul')->nullable();
            $table->timestamp('wektu_terbit')->nullable();
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
        Schema::dropIfExists('beritas');
    }
};
