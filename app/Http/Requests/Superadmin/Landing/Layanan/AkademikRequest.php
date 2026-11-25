<?php

namespace App\Http\Requests\Superadmin\Landing\Layanan;

use App\Models\Layanan\Akademik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AkademikRequest extends FormRequest
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
            'tandha'   => $rawSlug ? Str::slug((string) $rawSlug) : null,
            'kategori' => trim((string) ($this->input('kategori') ?: 'Umum')),
            'urutan'   => max(0, (int) $this->input('urutan', 0)),
            'urip'     => $this->boolean('urip'),
        ]);
    }

    public function rules(): array
    {
        /** @var Akademik|null $akademik */
        $akademik = $this->route('akademik');

        return [
            'jeneng'    => ['required', 'string', 'max:255'],
            'tandha'    => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('akademiks', 'tandha')->ignore($akademik?->id),
            ],
            'kategori'  => ['required', 'string', 'max:100'],
            'katrangan' => ['nullable', 'string', 'max:2000'],
            'file'      => [
                $akademik ? 'nullable' : 'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
                'max:10240', // Maksimal 10 MB
            ],
            'urutan'    => ['required', 'integer', 'min:0', 'max:99999'],
            'urip'      => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'jeneng.required' => 'Nama dokumen/layanan wajib diisi.',
            'tandha.unique'   => 'Slug URL (tandha) sudah digunakan dokumen lain.',
            'file.required'   => 'File dokumen wajib diunggah.',
            'file.mimes'      => 'Format file harus PDF, Word, Excel, PowerPoint, atau ZIP.',
            'file.max'        => 'Ukuran file maksimal 10 MB.',
        ];
    }
}
