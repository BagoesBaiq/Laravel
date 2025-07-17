<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'daerah_id',
        'nama',
        'deskripsi',
        'gambar',
        'kategori',
        'daerah',
        'resep',
    ];
    
    // Relationship with Daerah
    public function daerah()
    {
        return $this->belongsTo(Daerah::class,);
    }

    // Validation rules
    public static function validationRules($id = null)
    {
        return [
            'nama' => [
            'required',
            'string',
            'max:50',
            Rule::unique('produks', 'nama')
                ->where('daerah_id', request('daerah_id'))
                ->ignore($id)
        ],
        'daerah_id' => 'required|exists:daerahs,id',
        'kategori' => 'required|in:makanan,minuman',
        'gambar' => 'required|image|max:2048', // 2MB max
        'resep' => 'required|string',
        ];
    }

    // Scope for filtering by category
    public function scopeFindDuplicate($query, $nama, $daerahId, $excludeId = null)
    {
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }
}
