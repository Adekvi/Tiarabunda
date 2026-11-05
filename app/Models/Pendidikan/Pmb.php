<?php

namespace App\Models\Pendidikan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pmb extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'syarat',
        'langkah',
        'wektu_bukak',
        'wektu_tutup',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip'         => 'boolean',
            'urutan'       => 'integer',
            'syarat'       => 'array',
            'langkah'      => 'array',
            'wektu_bukak'  => 'date',
            'wektu_tutup'  => 'date',
        ];
    }

    /**
     * Format teks untuk isi awal RTE di form.
     * Mengembalikan teks HTML siap edit.
     */
    protected function syaratText(): Attribute
    {
        return Attribute::get(
            fn() => $this->arrayToHtml($this->syarat, 'ul')
        );
    }

    protected function langkahText(): Attribute
    {
        return Attribute::get(
            fn() => $this->arrayToHtml($this->langkah, 'ol')
        );
    }

    /**
     * Untuk render view publik (HTML siap pakai).
     */
    public function syaratHtml(): string
    {
        return $this->arrayToHtml($this->syarat, 'ul');
    }

    public function langkahHtml(): string
    {
        return $this->arrayToHtml($this->langkah, 'ol');
    }

    /**
     * @param  array<int,string>|null  $items
     */
    private function arrayToHtml(?array $items, string $tag = 'ul'): string
    {
        if (empty($items)) {
            return '';
        }

        $lis = collect($items)
            ->map(fn($s) => '<li>' . e($s) . '</li>')
            ->implode('');

        return "<{$tag}>{$lis}</{$tag}>";
    }
}
