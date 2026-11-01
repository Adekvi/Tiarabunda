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
            $table->string('name')->nullable();                 // PMB Dini, Prestasi, Reguler
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->json('requirements')->nullable(); // array syarat
            $table->json('steps')->nullable();        // array alur
            $table->date('open_at')->nullable();
            $table->date('close_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
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
