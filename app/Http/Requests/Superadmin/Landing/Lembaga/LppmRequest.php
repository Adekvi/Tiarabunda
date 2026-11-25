<?php

namespace App\Http\Requests\Superadmin\Landing\Lembaga;

use App\Models\Lembaga\Lppm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LppmRequest extends FormRequest
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

        $rawMeta = (array) $this->input('data_tambahan', []);
        $cleanMeta = [];

        foreach (['skema', 'pelaksana', 'tahun', 'sumber_dana', 'nomor_identitas', 'tautan', 'label_tautan'] as $key) {
            $val = isset($rawMeta[$key]) ? trim((string) $rawMeta[$key]) : null;
            if ($val !== null && $val !== '') {
                $cleanMeta[$key] = $val;
            }
        }

        $this->merge([
            'tandha'        => $rawSlug ? Str::slug((string) $rawSlug) : null,
            'kategori'      => $this->input('kategori', 'penelitian'),
            'data_tambahan' => empty($cleanMeta) ? null : $cleanMeta,
            'urutan'        => max(0, (int) $this->input('urutan', 0)),
            'urip'          => $this->boolean('urip'),
        ]);
    }

    public function rules(): array
    {
        /** @var Lppm|null $lppm */
        $lppm = $this->route('lppm');

        return [
            'judhul'                        => ['required', 'string', 'max:255'],
            'tandha'                        => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lppms', 'tandha')->ignore($lppm?->id),
            ],
            'kategori'                      => ['required', 'string', Rule::in(array_keys(Lppm::KATEGORI))],
            'isi'                           => ['nullable', 'string'],
            'data_tambahan'                 => ['nullable', 'array'],
            'data_tambahan.skema'           => ['nullable', 'string', 'max:150'],
            'data_tambahan.pelaksana'       => ['nullable', 'string', 'max:200'],
            'data_tambahan.tahun'           => ['nullable', 'string', 'max:30'],
            'data_tambahan.sumber_dana'     => ['nullable', 'string', 'max:150'],
            'data_tambahan.nomor_identitas' => ['nullable', 'string', 'max:120'],
            'data_tambahan.tautan'          => ['nullable', 'url', 'max:255'],
            'data_tambahan.label_tautan'    => ['nullable', 'string', 'max:80'],
            'urutan'                        => ['required', 'integer', 'min:0', 'max:99999'],
            'urip'                          => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'judhul.required'            => 'Judul program, layanan, atau kegiatan LPPM wajib diisi.',
            'tandha.unique'              => 'Slug URL (tandha) sudah digunakan entri LPPM lain.',
            'kategori.in'                => 'Kategori LPPM yang dipilih tidak valid.',
            'data_tambahan.tautan.url'   => 'Tautan harus berupa URL valid (diawali https:// atau http://).',
        ];
    }
}
