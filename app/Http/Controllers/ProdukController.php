<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{

    public function __construct()
    {
        // Tambahkan CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    }
    
    public function store(Request $request)
    {
        // Debug Upload
        Log::info('Upload attempt:', $request->all());

        if ($request->hasFile('image')) {
            Log::info('File details:', [
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'extension' => $request->file('image')->getClientOriginalExtension()
            ]);
        }
    }
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
