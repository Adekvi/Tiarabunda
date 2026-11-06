<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akreditasis', function (Blueprint $table) {
            $table->id();
            $table->string('jeneng');
            $table->string('status_akred', 50)->nullable();
            $table->string('bagean', 20)->default('institusi');
            $table->text('katrangan')->nullable();
            $table->string('path_gambar')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['urip', 'urutan']);
            $table->index(['bagean', 'urip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akreditasis');
    }
};
