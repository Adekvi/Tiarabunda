<?php

namespace App\Http\Requests\Superadmin\Landing\Lembaga;

use App\Models\Lembaga\Lpm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LpmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $rawSlug = $this->filled('tandha')
            ? $this->input('tandha')
            : $this->input('judhul');

        $rawMeta   = (array) $this->input('data_tambahan', []);
        $cleanMeta = [];

        $allowedKeys = [
            'kode_dokumen',
            'siklus_ppepp',
            'lembaga_akreditasi',
            'peringkat',
            'masa_berlaku',
            'tahun',
            'tautan',
            'label_tautan',
        ];

        foreach ($allowedKeys as $key) {
            $val = isset($rawMeta[$key]) ? trim((string) $rawMeta[$key]) : null;
            if ($val !== null && $val !== '') {
                $cleanMeta[$key] = $val;
            }
        }

        $this->merge([
            'tandha'        => $rawSlug ? Str::slug((string) $rawSlug) : null,
            'kategori'      => $this->input('kategori', 'spmi'),
            'data_tambahan' => empty($cleanMeta) ? null : $cleanMeta,
            'urutan'        => max(0, (int) $this->input('urutan', 0)),
            'urip'          => $this->boolean('urip'),
        ]);
    }

    public function rules(): array
    {
        /** @var Lpm|null $lpm */
        $lpm = $this->route('lpm');

        return [
            'judhul'                           => ['required', 'string', 'max:255'],
            'tandha'                           => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lpms', 'tandha')->ignore($lpm?->id),
            ],
            'kategori'                         => ['required', 'string', Rule::in(array_keys(Lpm::KATEGORI))],
            'isi'                              => ['nullable', 'string'],
            'file'                             => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,zip', 'max:10240'],
            'data_tambahan'                    => ['nullable', 'array'],
            'data_tambahan.kode_dokumen'       => ['nullable', 'string', 'max:120'],
            'data_tambahan.siklus_ppepp'       => ['nullable', 'string', 'max:100'],
            'data_tambahan.lembaga_akreditasi' => ['nullable', 'string', 'max:120'],
            'data_tambahan.peringkat'          => ['nullable', 'string', 'max:80'],
            'data_tambahan.masa_berlaku'       => ['nullable', 'string', 'max:100'],
            'data_tambahan.tahun'              => ['nullable', 'string', 'max:30'],
            'data_tambahan.tautan'             => ['nullable', 'url:http,https', 'max:255'],
            'data_tambahan.label_tautan'       => ['nullable', 'string', 'max:80'],
            'urutan'                           => ['required', 'integer', 'min:0', 'max:99999'],
            'urip'                             => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'judhul.required'          => 'Judul dokumen, program, atau informasi LPM wajib diisi.',
            'tandha.unique'            => 'Slug URL (tandha) sudah digunakan entri LPM lain.',
            'kategori.in'              => 'Kategori LPM yang dipilih tidak valid.',
            'file.mimes'               => 'Berkas mutu/akreditasi harus berformat PDF, Word, Excel, atau ZIP.',
            'file.max'                 => 'Ukuran berkas maksimal 10 MB.',
            'data_tambahan.tautan.url' => 'Tautan harus berupa URL valid (diawali https:// atau http://).',
        ];
    }
}
