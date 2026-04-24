<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class UserDendaController extends Controller
{
    // Menampilkan daftar denda user
    public function index()
    {
        $pengembalians = auth()->user()->peminjamans()
            ->with(['buku', 'pengembalian'])
            ->whereHas('pengembalian', function ($q) {
                $q->where('denda', '>', 0);
            })
            ->latest()
            ->paginate(10);

        $totalDenda = auth()->user()->peminjamans()
            ->whereHas('pengembalian', function ($q) {
                $q->where('denda', '>', 0)
                    ->where('status_pembayaran', '!=', 'sudah_dibayar');
            })
            ->with('pengembalian')
            ->get()
            ->sum(fn($p) => $p->pengembalian->denda ?? 0);

        return view('user.denda.index', compact('pengembalians', 'totalDenda'));
    }

    // Menampilkan detail denda dan form pembayaran
    public function show(Pengembalian $pengembalian)
    {
        // Pastikan pengembalian milik user yang login
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan ada denda
        if ($pengembalian->denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar!');
        }

        return view('user.denda.show', compact('pengembalian'));
    }

    // Menampilkan form pembayaran denda
    public function paymentForm(Pengembalian $pengembalian)
    {
        // Pastikan pengembalian milik user yang login
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan ada denda
        if ($pengembalian->denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar!');
        }

        // Pastikan belum dibayar
        if ($pengembalian->status_pembayaran === 'sudah_dibayar') {
            return back()->with('error', 'Denda sudah dibayar!');
        }

        return view('user.denda.payment', compact('pengembalian'));
    }

    // Menyimpan bukti pembayaran
    public function submitPayment(Request $request, Pengembalian $pengembalian)
    {
        // Pastikan pengembalian milik user yang login
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi
        $validated = $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpg, jpeg, png, atau gif',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Simpan file bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama jika ada
            if ($pengembalian->bukti_pembayaran) {
                Storage::delete('public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $filename = 'pembayaran_' . $pengembalian->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/bukti_pembayaran', $filename);

            $pengembalian->update([
                'bukti_pembayaran' => $filename,
                'status_pembayaran' => 'pending_approval',
                'tanggal_pembayaran' => now(),
            ]);

            return redirect()->route('user.denda.index')
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu persetujuan admin...');
        }

        return back()->withErrors(['bukti_pembayaran' => 'Gagal mengupload file']);
    }

    // Menampilkan riwayat pembayaran
    public function history()
    {
        $pengembalians = auth()->user()->peminjamans()
            ->with(['buku', 'pengembalian'])
            ->whereHas('pengembalian', function ($q) {
                $q->where('denda', '>', 0);
            })
            ->where('status', 'returned')
            ->latest()
            ->paginate(10);

        return view('user.denda.history', compact('pengembalians'));
    }
}
