<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku', 'approvedBy'])->latest();

        if ($request->filled('search')) {
            $search = (string) $request->string('search');
            $query->where(function ($outer) use ($search) {
                $outer->whereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        $peminjamans = $query->paginate(12)->withQueryString();

        $totalPeminjaman = Peminjaman::count();
        $pending = Peminjaman::where('status', 'pending')->count();
        $approved = Peminjaman::where('status', 'approved')->count();
        $returned = Peminjaman::where('status', 'returned')->count();

        $users = User::orderBy('name')->get();

        return view('admin.peminjaman.index', compact(
            'peminjamans',
            'totalPeminjaman',
            'pending',
            'approved',
            'returned',
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
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ((int) $validated['jumlah'] > $buku->stok) {
            return back()
                ->withErrors(['jumlah' => 'Stok buku tidak mencukupi. Stok tersedia saat ini: ' . $buku->stok . '.'])
                ->withInput();
        }

        Peminjaman::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dibuat dan menunggu persetujuan!');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya transaksi yang masih menunggu yang dapat diedit.');
        }

        $users = User::orderBy('name')->get();
        $bukus = Buku::orderBy('judul')->get();

        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'bukus'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya peminjaman pending yang dapat diedit!');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ((int) $validated['jumlah'] > $buku->stok) {
            return back()
                ->withErrors(['jumlah' => 'Stok buku tidak mencukupi.'])
                ->withInput();
        }

        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diupdate!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'approved') {
            $peminjaman->buku->increment('stok', $peminjaman->jumlah);
        }

        $peminjaman->delete();
        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus!');
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman ini sudah diproses!');
        }

        $buku = $peminjaman->buku;
        if ($buku->stok < $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Stok buku tidak mencukupi!');
        }

        $buku->decrement('stok', $peminjaman->jumlah);
        $peminjaman->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman disetujui!');
    }

    public function markReturned(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan!');
        }

        $buku = $peminjaman->buku;
        $buku->increment('stok', $peminjaman->jumlah);

        $peminjaman->update([
            'status' => 'returned',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditandai sebagai dikembalikan!');
    }
}
