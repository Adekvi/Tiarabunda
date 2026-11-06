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
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();

            $table->foreignId('akreditasi_id')
                ->nullable()
                ->constrained('akreditasis')
                ->nullOnDelete();

            $table->string('jeneng')->nullable();
            $table->string('tandha')->unique()->nullable();
            $table->string('undhak', 10)->nullable();
            $table->text('katrangan')->nullable();
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
        Schema::dropIfExists('prodis');
    }
};
