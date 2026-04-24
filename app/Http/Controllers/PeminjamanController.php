<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Models\Buku;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->latest()
            ->paginate(12);

        return view('admin.peminjaman.index', [
            'peminjamans' => $peminjamans,
            'totalPeminjaman' => Peminjaman::count(),
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'approved' => Peminjaman::where('status', 'dipinjam')->count(),
            'returned' => Peminjaman::where('status', 'returned')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', now())->count(),
            'users' => User::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('admin.peminjaman.create', [
            'users' => User::orderBy('name')->get(),
            'bukus' => Buku::where('stok', '>', 0)->orderBy('judul')->get()
        ]);
    }

    // 🔥 STORE (TIDAK MENGURANGI STOK)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'durasi' => 'required|numeric|min:1'
        ]);

        $now = Carbon::now();

        Peminjaman::create([
            'user_id' => $validated['user_id'],
            'buku_id' => $validated['buku_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $now,
            'tanggal_kembali' => $now->copy()->addMinutes((int) $validated['durasi']),
            'status' => 'pending'
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan berhasil, menunggu approve!');
    }

    // 🔥 APPROVE (DI SINI STOK DIKURANGI)
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa di-approve!');
        }

        DB::transaction(function () use ($peminjaman) {

            $buku = $peminjaman->buku;

            // ❗ CEK STOK LAGI
            if ($buku->stok < $peminjaman->jumlah) {
                throw new \Exception('Stok tidak mencukupi!');
            }

            // 🔥 KURANGI STOK DI SINI
            $buku->decrement('stok', $peminjaman->jumlah);

            $peminjaman->update([
                'status' => 'dipinjam'
            ]);
        });

        return back()->with('success', 'Peminjaman berhasil di-approve!');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'buku', 'pengembalian']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // 🔥 BALIKIN STOK JIKA SUDAH DIPINJAM
        if ($peminjaman->status === 'dipinjam') {
            $peminjaman->buku->increment('stok', $peminjaman->jumlah);
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    public function markReturned(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'returned') {
            return back()->with('error', 'Sudah dikembalikan!');
        }

        DB::transaction(function () use ($peminjaman) {

            $returnedAt = Carbon::now();

            // 🔥 BALIKKAN STOK
            $peminjaman->buku->increment('stok', $peminjaman->jumlah);

            $denda = 0;

            if ($returnedAt->gt($peminjaman->tanggal_kembali)) {
                $lateSeconds = $returnedAt->diffInSeconds($peminjaman->tanggal_kembali);
                $denda = $lateSeconds * 10;
            }

            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'tanggal_pengembalian' => $returnedAt,
                    'denda' => $denda,
                    'status_pembayaran' => $denda > 0 ? 'belum_dibayar' : 'sudah_dibayar',
                ]
            );

            $peminjaman->update([
                'status' => 'returned'
            ]);
        });

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan!');
    }

    public function checkDamageForm(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'returned') {
            return back()->with('error', 'Buku harus sudah dikembalikan!');
        }

        if (!$peminjaman->pengembalian) {
            return back()->with('error', 'Data pengembalian tidak ditemukan!');
        }

        return view('admin.peminjaman.check-damage', [
            'peminjaman' => $peminjaman,
            'pengembalian' => $peminjaman->pengembalian
        ]);
    }

    public function saveDamage(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'ada_kerusakan' => 'required|boolean',
            'deskripsi_kerusakan' => 'nullable|string|max:1000',
        ]);

        $pengembalian = $peminjaman->pengembalian;

        if (!$pengembalian) {
            return back()->with('error', 'Data pengembalian tidak ditemukan!');
        }

        $denda = (int) $pengembalian->denda;

        if ($validated['ada_kerusakan']) {
            $denda += 50000;
        }

        $pengembalian->update([
            'ada_kerusakan' => $validated['ada_kerusakan'],
            'deskripsi_kerusakan' => $validated['deskripsi_kerusakan'],
            'denda' => $denda,
            'status_pembayaran' => $denda > 0 ? 'belum_dibayar' : 'sudah_dibayar',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data kerusakan berhasil disimpan!');
    }
}