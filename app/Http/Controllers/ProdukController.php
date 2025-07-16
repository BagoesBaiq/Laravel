<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
    
        // Pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
    
        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
    
        // Pengurutan
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'az':
                    $query->orderBy('nama', 'asc');
                    break;
                case 'za':
                    $query->orderBy('nama', 'desc');
                    break;
            }
        }
    
        // Ambil produk setelah filter
        $produk = $query->get();
    
        // Ambil kategori unik untuk dropdown
        $kategoris = Produk::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        // Ambil daerahs unik untuk dropdown
        $daerahs = Produk::whereNotNull('daerah')
            ->where('daerah', '!=', '')
            ->distinct()
            ->orderBy('daerah')
            ->pluck('daerah');
    
        return view('katalog.index', compact('produk', 'kategoris', 'daerahs'));
    }
}
