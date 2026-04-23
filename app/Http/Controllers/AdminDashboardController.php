<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Kategori;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::sum('stok');
        $totalJudul = Buku::count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::whereIn('status', ['pending', 'approved'])->count();
        $peminjamanTerlambat = Peminjaman::with('pengembalian')
            ->get()
            ->filter(fn (Peminjaman $peminjaman) => $peminjaman->isLate())
            ->count();
        
        $bukuTerlaris = Buku::withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->limit(5)
            ->get();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $statistikPerStatus = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'approved' => Peminjaman::where('status', 'approved')->count(),
            'returned' => Peminjaman::where('status', 'returned')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalJudul',
            'totalAnggota',
            'totalAdmin',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanTerlambat',
            'bukuTerlaris',
            'peminjamanTerbaru',
            'statistikPerStatus'
        ));
    }
}
