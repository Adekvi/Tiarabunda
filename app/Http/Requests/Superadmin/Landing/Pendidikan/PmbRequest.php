<?php

namespace App\Http\Requests\Superadmin\Landing\Pendidikan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PmbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Sanitasi HTML dari RTE. Longgarkan daftar tag sesuai kebutuhan.
        $allowed = '<p><br><strong><b><em><i><u><s><strike><ul><ol><li><a><h2><h3><blockquote>';

        $this->merge([
            'katrangan'    => $this->cleanHtml((string) $this->input('katrangan'), $allowed),
            'syarat_text'  => $this->cleanHtml((string) $this->input('syarat_text'), $allowed),
            'langkah_text' => $this->cleanHtml((string) $this->input('langkah_text'), $allowed),
        ]);
    }

    public function rules(): array
    {
        $pmb = $this->route('pmb');
        $id = is_object($pmb) ? $pmb->id : $pmb;

        return [
            'jeneng' => ['required', 'string', 'max:150'],
            'tandha' => [
                'nullable',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('pmbs', 'tandha')->ignore($id),
            ],
            'katrangan'    => ['nullable', 'string'],
            'syarat_text'  => ['nullable', 'string'],
            'langkah_text' => ['nullable', 'string'],
            'wektu_bukak'  => ['nullable', 'date'],
            'wektu_tutup'  => ['nullable', 'date', 'after_or_equal:wektu_bukak'],
            'urutan'       => ['nullable', 'integer', 'min:0', 'max:9999'],
            'urip'         => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'jeneng.required'          => 'Nama jalur wajib diisi.',
            'wektu_tutup.after_or_equal' => 'Tanggal tutup tidak boleh sebelum tanggal buka.',
            'tandha.alpha_dash'        => 'Slug hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'tandha.unique'            => 'Slug sudah digunakan, pilih yang lain.',
        ];
    }

    /**
     * Sanitasi ringan tanpa dependency tambahan.
     * - strip tag yang tidak diizinkan
     * - hapus atribut on* dan style
     * - hapus href="javascript:"
     */
    private function cleanHtml(string $html, string $allowed): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        // Buang tag yang tidak diizinkan
        $html = strip_tags($html, $allowed);

        // Bersihkan atribut berbahaya (loop sampai stabil untuk nested)
        $prev = null;
        while ($prev !== $html) {
            $prev = $html;
            $html = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
            $html = preg_replace('/\s+(href|src)\s*=\s*(["\']?)\s*javascript:[^"\'>\s]*\2/i', '', $html);
        }

        return $html;
    }
}
