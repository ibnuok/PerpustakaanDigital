<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->with(['buku', 'pengembalian'])
            ->latest()
            ->get();

        $peminjamanSelesai = Peminjaman::where('user_id', $user->id)
            ->where('status', 'returned')
            ->count();

        $peminjamanTerbaru = Peminjaman::where('user_id', $user->id)
            ->with(['buku', 'pengembalian'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $tagihanAktif = $user->peminjamans()
            ->with(['buku', 'pengembalian'])
            ->whereHas('pengembalian', function ($query) {
                $query->where('denda', '>', 0)
                    ->where('status_pembayaran', '!=', 'sudah_dibayar');
            })
            ->latest()
            ->get();

        $totalTagihan = $tagihanAktif->sum(fn ($item) => $item->pengembalian->denda ?? 0);
        $bukuTersedia = Buku::where('stok', '>', 0)->count();

        return view('user.dashboard', compact(
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerbaru',
            'tagihanAktif',
            'totalTagihan',
            'bukuTersedia'
        ));
    }
}
