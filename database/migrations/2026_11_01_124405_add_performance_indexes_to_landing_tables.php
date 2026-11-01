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
        $simple = [
            'menus',
            'submenus',
            'faqs',
            'beritas',
            'kegiatans',
            'fasilitas',
            'dokumens',
            'pimpinans',
            'sejarahs',
            'visimisis',
            'strukturs',
            'fakultas',
            'prodis',
            'pmbs',
            'beasiswas',
            'perpuses',
            'kariers',
            'kemahasiswaans',
        ];

        foreach ($simple as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'urip') && Schema::hasColumn($table, 'urutan')) {
                    $t->index(['urip', 'urutan'], $table . '_urip_urutan_idx');
                }
            });
        }

        if (Schema::hasTable('submenus')) {
            Schema::table('submenus', function (Blueprint $t) {
                $t->index(['menu_id', 'urip', 'urutan'], 'submenus_menu_urip_urutan_idx');
            });
        }

        if (Schema::hasTable('prodis')) {
            Schema::table('prodis', function (Blueprint $t) {
                $t->index(['fakultas_id', 'urip', 'urutan'], 'prodis_fak_urip_urutan_idx');
            });
        }

        if (Schema::hasTable('beritas') && Schema::hasColumn('beritas', 'wektu_terbit')) {
            Schema::table('beritas', function (Blueprint $t) {
                $t->index(['urip', 'wektu_terbit'], 'beritas_urip_terbit_idx');
            });
        }
    }

    public function down(): void
    {
        $simple = [
            'menus',
            'submenus',
            'faqs',
            'beritas',
            'kegiatans',
            'fasilitas',
            'dokumens',
            'pimpinans',
            'sejarahs',
            'visimisis',
            'strukturs',
            'fakultas',
            'prodis',
            'pmbs',
            'beasiswas',
            'perpuses',
            'kariers',
            'kemahasiswaans',
        ];

        foreach ($simple as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->dropIndex($table . '_urip_urutan_idx');
            });
        }

        if (Schema::hasTable('submenus')) {
            Schema::table('submenus', function (Blueprint $t) {
                $t->dropIndex('submenus_menu_urip_urutan_idx');
            });
        }

        if (Schema::hasTable('prodis')) {
            Schema::table('prodis', function (Blueprint $t) {
                $t->dropIndex('prodis_fak_urip_urutan_idx');
            });
        }

        if (Schema::hasTable('beritas')) {
            Schema::table('beritas', function (Blueprint $t) {
                $t->dropIndex('beritas_urip_terbit_idx');
            });
        }
    }
};
