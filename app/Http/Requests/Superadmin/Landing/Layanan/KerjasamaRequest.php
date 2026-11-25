<?php

namespace App\Http\Requests\Superadmin\Landing\Layanan;

use App\Models\Layanan\Kerjasama;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KerjasamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawSlug = $this->filled('tandha')
            ? $this->input('tandha')
            : $this->input('jeneng');

        $this->merge([
            'jenis'         => $this->input('jenis', Kerjasama::JENIS_MITRA),
            'tandha'        => $rawSlug ? Str::slug((string) $rawSlug) : null,
            'kategori'      => trim((string) ($this->input('kategori') ?: 'Industri & Perusahaan')),
            'nomor_dokumen' => filled($this->input('nomor_dokumen')) ? trim((string) $this->input('nomor_dokumen')) : null,
            'periode'       => filled($this->input('periode')) ? trim((string) $this->input('periode')) : null,
            'tautan'        => filled($this->input('tautan')) ? trim((string) $this->input('tautan')) : null,
            'kontak_pic'    => filled($this->input('kontak_pic')) ? trim((string) $this->input('kontak_pic')) : null,
            'urutan'        => max(0, (int) $this->input('urutan', 0)),
            'urip'          => $this->boolean('urip'),
        ]);
    }

    public function rules(): array
    {
        /** @var Kerjasama|null $kerjasama */
        $kerjasama = $this->route('kerjasama');

        return [
            'jenis'         => ['required', Rule::in([Kerjasama::JENIS_MITRA, Kerjasama::JENIS_PENAWARAN])],
            'jeneng'        => ['required', 'string', 'max:255'],
            'tandha'        => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('kerjasamas', 'tandha')->ignore($kerjasama?->id),
            ],
            'kategori'      => ['required', 'string', 'max:100'],
            'nomor_dokumen' => ['nullable', 'string', 'max:120'],
            'periode'       => ['nullable', 'string', 'max:100'],
            'katrangan'     => ['nullable', 'string', 'max:3000'],
            'logo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'file'          => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip', 'max:10240'],
            'tautan'        => ['nullable', 'url', 'max:255'],
            'kontak_pic'    => ['nullable', 'string', 'max:150'],
            'urutan'        => ['required', 'integer', 'min:0', 'max:99999'],
            'urip'          => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'jeneng.required' => 'Nama instansi/perusahaan atau judul penawaran wajib diisi.',
            'tandha.unique'   => 'Slug URL (tandha) sudah digunakan data lain.',
            'logo.image'      => 'File logo/sampul harus berupa gambar (JPG, PNG, WEBP, SVG).',
            'logo.max'        => 'Ukuran logo maksimal 2 MB.',
            'file.mimes'      => 'Dokumen MoU/proposal harus berformat PDF, Word, PowerPoint, atau ZIP.',
            'file.max'        => 'Ukuran dokumen maksimal 10 MB.',
            'tautan.url'      => 'Format tautan harus berupa URL valid (diawali https:// atau http://).',
        ];
    }
}
