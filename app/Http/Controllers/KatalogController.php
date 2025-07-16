<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Daerah; // Pastikan model Daerah di-import
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. <-- DIUBAH: Ambil daftar daerah langsung dari tabel 'daerahs'
        // Ini adalah cara yang benar dan efisien.
        $daerahs = Daerah::orderBy('nama_daerah')->get();

        // Mulai query produk, dan langsung sertakan data daerah terkaitnya
        // .with('daerah') disebut Eager Loading, ini sangat penting untuk performa
        $query = Produk::with('daerah');

        // 2. <-- DIUBAH: Filter berdasarkan 'daerah_id'
        // Filter sekarang menggunakan ID, bukan nama daerah lagi.
        if ($request->filled('daerah')) {
            $query->where('daerah_id', $request->daerah);
        }

        // Pencarian berdasarkan nama (tidak ada perubahan, sudah benar)
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Sorting harga (tidak ada perubahan)
        // Pastikan Anda memiliki kolom 'harga' di tabel 'produks'
        if ($request->sort == 'termahal') {
            $query->orderBy('harga', 'desc');
        } elseif ($request->sort == 'termurah') {
            $query->orderBy('harga', 'asc');
        }

        // Ambil hasil akhir
        $produks = $query->latest()->get(); // Menggunakan nama variabel jamak ($produks)

        return view('katalog.index', compact('produks', 'daerahs')); // Menggunakan $produks
    }
}