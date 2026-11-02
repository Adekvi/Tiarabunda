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
        Schema::create('visimisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dianyari_dening')->nullable()->constrained('users')->nullOnDelete();
            $table->string('bagean')->nullable();      // visi | misi
            $table->string('judhul')->nullable();
            $table->text('isi')->nullable();
            $table->json('dhaptar')->nullable();
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
        Schema::dropIfExists('visimisis');
    }
};
