<?php

namespace App\Http\Requests\Superadmin\Landing\Layanan;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FasilitasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->can('access-superadmin');
    }

    public function rules(): array
    {
        return [
            'jeneng'     => ['required', 'string', 'max:150'],
            'katrangan'  => ['nullable', 'string', 'max:5000'],
            'path_gambar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'urutan'     => ['nullable', 'integer', 'min:0', 'max:9999'],
            'urip'       => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'jeneng.required'  => 'Nama fasilitas wajib diisi.',
            'jeneng.max'       => 'Nama fasilitas maksimal 150 karakter.',
            'path_gambar.image' => 'File harus berupa gambar.',
            'path_gambar.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'path_gambar.max'  => 'Ukuran gambar maksimal 2 MB.',
            'urutan.integer'   => 'Urutan harus berupa angka.',
            'urutan.min'       => 'Urutan minimal 0.',
            'urutan.max'       => 'Urutan maksimal 9999.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'urip'   => $this->boolean('urip'),
            'urutan' => $this->input('urutan', 0),
        ]);
    }
}
