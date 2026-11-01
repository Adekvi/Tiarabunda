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
        Schema::create('submenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('judhul');
            $table->string('tandha');
            $table->string('sub_judhul')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->json('blok_isi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['menu_id', 'tandha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submenus');
    }
};
