<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Produk;

class Daerah extends Model
{
    use HasFactory;

    protected $table = 'daerah';

    protected $fillable = [
        'nama_daerah',
    ];

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    public function makanan()
    {
        return $this->produks()->where('kategori', 'Makanan');
    }
}
