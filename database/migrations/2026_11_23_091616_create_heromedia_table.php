<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->unsignedTinyInteger('durasi_slider')->default(6)->after('path_poster');
        });

        Schema::create('heromedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_id')->constrained('heroes')->cascadeOnDelete();
            $table->enum('tipe', ['video', 'gambar']);
            $table->string('path_media');
            $table->string('path_poster')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('urip')->default(true);
            $table->timestamps();

            $table->index(['hero_id', 'urip', 'tipe', 'urutan']);
        });

        // Migrasi otomatis data lama dari tabel heroes ke heromedia
        $now = now();
        DB::table('heroes')->get()->each(function ($row) use ($now) {
            if (in_array($row->tipe_media, ['video', 'gambar'], true) && ! empty($row->path_media)) {
                DB::table('heromedia')->insert([
                    'hero_id'     => $row->id,
                    'tipe'        => $row->tipe_media,
                    'path_media'  => $row->path_media,
                    'path_poster' => $row->tipe_media === 'video' ? $row->path_poster : null,
                    'urutan'      => $row->tipe_media === 'video' ? 0 : 1,
                    'urip'        => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heromedia');

        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn('durasi_slider');
        });
    }
};
