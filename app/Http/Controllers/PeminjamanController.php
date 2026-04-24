<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.peminjaman.create', [
            'users' => User::orderBy('name')->get(),
            'bukus' => Buku::where('stok', '>', 0)->orderBy('judul')->get(),
        ]);
    }

    public function edit(Peminjaman $peminjaman)
    {
        return view('admin.peminjaman.edit', [
            'peminjaman' => $peminjaman,
            'users' => User::orderBy('name')->get(),
            'bukus' => Buku::orderBy('judul')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'durasi' => 'required|numeric|min:1',
        ]);

        $now = Carbon::now();

        Peminjaman::create([
            'user_id' => $validated['user_id'],
            'buku_id' => $validated['buku_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $now,
            'tanggal_kembali' => $now->copy()->addMinutes((int) $validated['durasi']),
            'status' => 'pending',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan berhasil, menunggu approve!');
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa di-approve!');
        }

        DB::transaction(function () use ($peminjaman) {
            $buku = $peminjaman->buku;

            if ($buku->stok < $peminjaman->jumlah) {
                throw new \Exception('Stok tidak mencukupi!');
            }

            $buku->decrement('stok', $peminjaman->jumlah);

            $peminjaman->update([
                'status' => 'dipinjam',
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
            $lateFine = $peminjaman->calculateLateFine($returnedAt);

            $peminjaman->buku->increment('stok', $peminjaman->jumlah);

            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'tanggal_pengembalian' => $returnedAt,
                    'denda_telat' => $lateFine,
                    'denda_kerusakan' => 0,
                    'denda' => $lateFine,
                    'ada_kerusakan' => false,
                    'deskripsi_kerusakan' => null,
                    'status_pembayaran' => $lateFine > 0 ? 'belum_dibayar' : 'sudah_dibayar',
                    'metode_pembayaran' => null,
                    'tanggal_pembayaran' => null,
                    'bukti_pembayaran' => null,
                    'catatan_penolakan' => null,
                ]
            );

            $peminjaman->update([
                'status' => 'returned',
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

        if (! $peminjaman->pengembalian) {
            return back()->with('error', 'Data pengembalian tidak ditemukan!');
        }

        return view('admin.peminjaman.check-damage', [
            'peminjaman' => $peminjaman,
            'pengembalian' => $peminjaman->pengembalian,
        ]);
    }

    public function saveDamage(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'ada_kerusakan' => 'required|boolean',
            'deskripsi_kerusakan' => 'nullable|string|max:1000|required_if:ada_kerusakan,1',
            'denda_kerusakan' => 'nullable|integer|min:0',
        ]);

        $pengembalian = $peminjaman->pengembalian;

        if (! $pengembalian) {
            return back()->with('error', 'Data pengembalian tidak ditemukan!');
        }

        $lateFine = (int) ($pengembalian->denda_telat ?: $pengembalian->denda);
        $damageFine = (int) ($validated['ada_kerusakan'] ? ($validated['denda_kerusakan'] ?? Peminjaman::DENDA_KERUSAKAN) : 0);
        $totalFine = $lateFine + $damageFine;

        $pengembalian->update([
            'ada_kerusakan' => (bool) $validated['ada_kerusakan'],
            'deskripsi_kerusakan' => $validated['ada_kerusakan'] ? $validated['deskripsi_kerusakan'] : null,
            'denda_telat' => $lateFine,
            'denda_kerusakan' => $damageFine,
            'denda' => $totalFine,
            'status_pembayaran' => $totalFine > 0 ? 'belum_dibayar' : 'sudah_dibayar',
            'metode_pembayaran' => $totalFine > 0 ? $pengembalian->metode_pembayaran : null,
            'catatan_penolakan' => null,
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data kerusakan berhasil disimpan!');
    }
}
