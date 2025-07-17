<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('produks', 'nama')
                    ->where(function ($query) {
                        return $query->where('daerah_id', $this->daerah_id);
                    })->ignore($this->route('produk')),
            ],
            'daerah_id' => 'required|exists:daerahs,id',
            'kategori' => 'required|in:makanan,minuman',
            'gambar' => 'required|image|max:2048', // 2MB max
            'resep' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'nama.unique' => 'Nama produk sudah ada di daerah ini.',
            'daerah_id.required' => 'Daerah harus dipilih.',
            'kategori.required' => 'Kategori harus dipilih.',
            'gambar.required' => 'Gambar produk harus diunggah.',
            'resep.required' => 'Resep produk harus diisi.',
        ];
    }
}
