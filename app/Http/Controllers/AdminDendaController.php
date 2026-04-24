<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.buku'])
            ->where('denda', '>', 0)
            ->whereHas('peminjaman.user')
            ->whereHas('peminjaman.buku');

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        } else {
            $query->where('status_pembayaran', 'pending_approval');
        }

        $pengembalians = $query->latest()->paginate(12)->withQueryString();

        $totalDenda = Pengembalian::where('denda', '>', 0)->sum('denda');
        $belumDibayar = Pengembalian::where('status_pembayaran', 'belum_dibayar')->where('denda', '>', 0)->count();
        $pendingApproval = Pengembalian::where('status_pembayaran', 'pending_approval')->where('denda', '>', 0)->count();
        $sudahDibayar = Pengembalian::where('status_pembayaran', 'sudah_dibayar')->where('denda', '>', 0)->count();

        return view('admin.denda.index', compact(
            'pengembalians',
            'totalDenda',
            'belumDibayar',
            'pendingApproval',
            'sudahDibayar'
        ));
    }

    public function show(Pengembalian $denda)
    {
        $pengembalian = $denda;
        $pengembalian->load(['peminjaman.user', 'peminjaman.buku']);

        if (! $pengembalian->peminjaman || ! $pengembalian->peminjaman->user || ! $pengembalian->peminjaman->buku) {
            return redirect()->route('admin.denda.index')
                ->with('error', 'Data denda tidak lengkap, sehingga tidak bisa diverifikasi.');
        }

        return view('admin.denda.show', compact('pengembalian'));
    }

    public function approve(Pengembalian $pengembalian)
    {
        $pengembalian->loadMissing(['peminjaman.user', 'peminjaman.buku']);

        if (! $pengembalian->peminjaman || ! $pengembalian->peminjaman->user || ! $pengembalian->peminjaman->buku) {
            return back()->with('error', 'Data denda tidak lengkap, verifikasi tidak dapat dilakukan.');
        }

        if ($pengembalian->status_pembayaran !== 'pending_approval') {
            return back()->with('error', 'Status pembayaran tidak valid untuk diapprove!');
        }

        if ($pengembalian->metode_pembayaran === 'transfer' && ! $pengembalian->bukti_pembayaran) {
            return back()->with('error', 'Bukti pembayaran transfer tidak ditemukan!');
        }

        $pengembalian->update([
            'status_pembayaran' => 'sudah_dibayar',
            'catatan_penolakan' => null,
        ]);

        return redirect()->route('admin.denda.show', ['denda' => $pengembalian->id])
            ->with('success', 'Pembayaran berhasil disetujui!');
    }

    public function reject(Request $request, Pengembalian $pengembalian)
    {
        $pengembalian->loadMissing(['peminjaman.user', 'peminjaman.buku']);

        if (! $pengembalian->peminjaman || ! $pengembalian->peminjaman->user || ! $pengembalian->peminjaman->buku) {
            return back()->with('error', 'Data denda tidak lengkap, verifikasi tidak dapat dilakukan.');
        }

        if ($pengembalian->status_pembayaran !== 'pending_approval') {
            return back()->with('error', 'Status pembayaran tidak valid untuk ditolak!');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        if ($pengembalian->bukti_pembayaran) {
            Storage::delete('public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);
        }

        $pengembalian->update([
            'status_pembayaran' => 'belum_dibayar',
            'bukti_pembayaran' => null,
            'metode_pembayaran' => null,
            'tanggal_pembayaran' => null,
            'catatan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return redirect()->route('admin.denda.show', ['denda' => $pengembalian->id])
            ->with('success', 'Pembayaran ditolak dan status kembali menjadi belum lunas.');
    }

    public function viewBukti(Pengembalian $pengembalian)
    {
        if (! $pengembalian->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $path = storage_path('app/public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);

        if (! file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

    public function downloadBukti(Pengembalian $pengembalian)
    {
        $pengembalian->loadMissing(['peminjaman.user']);

        if (! $pengembalian->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        if (! $pengembalian->peminjaman || ! $pengembalian->peminjaman->user) {
            abort(404, 'Data peminjam tidak ditemukan');
        }

        return Storage::download(
            'public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran,
            'bukti_pembayaran_' . str($pengembalian->peminjaman->user->name)->slug('_') . '.jpg'
        );
    }
}
