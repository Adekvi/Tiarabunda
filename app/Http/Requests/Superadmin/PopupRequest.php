<?php

namespace App\Http\Requests\Superadmin;

use App\Rules\SafeUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $urlRule = ['nullable', 'string', 'max:255', new SafeUrl];

        return [
            'kategori'      => ['required', 'string', 'max:40', Rule::in(['pmb', 'jurusan', 'beasiswa', 'info', 'custom'])],
            'badge'         => ['nullable', 'string', 'max:80'],
            'judul'         => ['required', 'string', 'max:180'],
            'deskripsi'     => ['nullable', 'string', 'max:2000'],
            'gambar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar'  => ['nullable', 'boolean'],
            'teks_tombol'   => ['nullable', 'string', 'max:80'],
            'url_tombol'    => $urlRule,
            'buka_tab_baru' => ['nullable', 'boolean'],
            'teks_sekunder' => ['nullable', 'string', 'max:80'],
            'url_sekunder'  => $urlRule,
            'urutan'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'buka_tab_baru' => $this->boolean('buka_tab_baru'),
            'is_active'     => $this->boolean('is_active'),
            'hapus_gambar'  => $this->boolean('hapus_gambar'),
            'urutan'        => $this->filled('urutan') ? (int) $this->input('urutan') : 0,
        ]);
    }
}
