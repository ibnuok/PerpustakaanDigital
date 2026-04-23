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
            ->whereIn('status', ['pending', 'approved'])
            ->with(['buku', 'pengembalian'])
            ->get();

        $peminjamanSelesai = Peminjaman::where('user_id', $user->id)
            ->where('status', 'returned')
            ->count();

        $peminjamanTerbaru = Peminjaman::where('user_id', $user->id)
            ->with(['buku', 'pengembalian'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $bukuTersedia = Buku::where('stok', '>', 0)->count();

        return view('user.dashboard', compact(
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerbaru',
            'bukuTersedia'
        ));
    }
}
