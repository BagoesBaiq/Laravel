<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    
    public function daerah()
    {
        return $this->belongsTo(Daerah::class,);
    }
}
