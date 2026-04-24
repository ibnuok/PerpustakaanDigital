<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengembalian;

class AdminDendaController extends Controller
{
    // Menampilkan daftar denda untuk approval
    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.buku']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        } else {
            // Default: tampilkan yang pending approval
            $query->where('status_pembayaran', 'pending_approval');
        }

        $pengembalians = $query->latest()->paginate(12);

        // Hitung statistik
        $totalDenda = Pengembalian::where('denda', '>', 0)->sum('denda');
        $belumDibayar = Pengembalian::where('status_pembayaran', 'belum_dibayar')->count();
        $pendingApproval = Pengembalian::where('status_pembayaran', 'pending_approval')->count();
        $sudahDibayar = Pengembalian::where('status_pembayaran', 'sudah_dibayar')->count();

        return view('admin.denda.index', compact(
            'pengembalians',
            'totalDenda',
            'belumDibayar',
            'pendingApproval',
            'sudahDibayar'
        ));
    }

    // Menampilkan detail denda untuk approval
    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.user', 'peminjaman.buku']);
        return view('admin.denda.show', compact('pengembalian'));
    }

    // Approve pembayaran
    public function approve(Pengembalian $pengembalian)
    {
        if ($pengembalian->status_pembayaran !== 'pending_approval') {
            return back()->with('error', 'Status pembayaran tidak valid untuk diapprove!');
        }

        if (!$pengembalian->bukti_pembayaran) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan!');
        }

        $pengembalian->update([
            'status_pembayaran' => 'sudah_dibayar',
        ]);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Pembayaran berhasil disetujui!');
    }

    // Reject pembayaran
    public function reject(Request $request, Pengembalian $pengembalian)
    {
        if ($pengembalian->status_pembayaran !== 'pending_approval') {
            return back()->with('error', 'Status pembayaran tidak valid untuk ditolak!');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        // Hapus bukti pembayaran
        if ($pengembalian->bukti_pembayaran) {
            Storage::delete('public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);
        }

        $pengembalian->update([
            'status_pembayaran' => 'belum_dibayar',
            'bukti_pembayaran' => null,
            'tanggal_pembayaran' => null,
        ]);

        // TODO: Kirim notifikasi ke user dengan alasan penolakan

        return redirect()->route('admin.denda.index')
            ->with('success', 'Pembayaran ditolak, user dapat mengupload bukti pembayaran ulang');
    }

    // Lihat bukti pembayaran
    public function viewBukti(Pengembalian $pengembalian)
    {
        if (!$pengembalian->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $path = storage_path('app/public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

    // Download bukti pembayaran
    public function downloadBukti(Pengembalian $pengembalian)
    {
        if (!$pengembalian->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        return Storage::download(
            'public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran,
            'bukti_pembayaran_' . $pengembalian->peminjaman->user->name . '.jpg'
        );
    }
}
