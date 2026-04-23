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
    public function index(Request $request)
    {
        $peminjamans = Peminjaman::with(['user', 'buku', 'pengembalian'])
            ->latest()
            ->paginate(12);

        $totalPeminjaman = Peminjaman::count();
        $pending = Peminjaman::where('status', 'pending')->count();
        $approved = Peminjaman::where('status', 'dipinjam')->count();
        $returned = Peminjaman::where('status', 'returned')->count();

        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->count();

        $users = User::orderBy('name')->get();

        return view('admin.peminjaman.index', compact(
            'peminjamans',
            'totalPeminjaman',
            'pending',
            'approved',
            'returned',
            'terlambat',
            'users'
        ));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $bukus = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('admin.peminjaman.create', compact('users', 'bukus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'durasi' => 'required|numeric|min:1' // 🔥 FIX
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok < $validated['jumlah']) {
            return back()->with('error', 'Stok buku tidak mencukupi!');
        }

        $now = Carbon::now();

        DB::transaction(function () use ($validated, $buku, $now) {

            Peminjaman::create([
                'user_id' => $validated['user_id'],
                'buku_id' => $validated['buku_id'],
                'jumlah' => $validated['jumlah'],

                // 🔥 WAKTU REAL
                'tanggal_pinjam' => $now->copy(),

                // 🔥 FIX ERROR + GANTI MENIT (lebih masuk akal)
                'tanggal_kembali' => $now->copy()->addMinutes((int) $validated['durasi']),

                'status' => 'pending'
            ]);

            $buku->decrement('stok', $validated['jumlah']);
        });

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan berhasil, menunggu approve!');
    }

    // 🔥 APPROVE
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa di-approve!');
        }

        $peminjaman->update([
            'status' => 'dipinjam'
        ]);

        return back()->with('success', 'Peminjaman berhasil di-approve!');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'buku', 'pengembalian']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'returned') {
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
                ]
            );

            $peminjaman->update([
                'status' => 'returned'
            ]);
        });

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan!');
    }
}