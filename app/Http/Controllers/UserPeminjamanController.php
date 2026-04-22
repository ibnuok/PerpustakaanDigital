<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Kategori;

class UserPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->peminjamans()->with('buku.kategori')->latest();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->whereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        $peminjamans = $query->paginate(10)->withQueryString();
        return view('user.peminjaman.index', compact('peminjamans'));
    }

    public function bukus(Request $request)
    {
        $query = Buku::with('kategori')->where('stok', '>', 0);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $bukus = $query->orderBy('judul')->paginate(12)->withQueryString();
        $kategoris = Kategori::all();

        return view('user.bukus', compact('bukus', 'kategoris'));
    }

    public function create(Request $request)
    {
        $buku_id = $request->query('buku_id');
        $buku = Buku::where('stok', '>', 0)->findOrFail($buku_id);

        return view('user.peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);
        if ($validated['jumlah'] > $buku->stok) {
            return back()->withErrors(['jumlah' => 'Stok buku tidak mencukupi! Stok tersedia: ' . $buku->stok . ' unit.'])->withInput();
        }

        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $validated['buku_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if (! $peminjaman->isPending()) {
            return redirect()->route('user.peminjaman.index')->with('error', 'Hanya transaksi menunggu yang dapat diubah.');
        }

        $buku = Buku::findOrFail($peminjaman->buku_id);

        return view('user.peminjaman.edit', compact('peminjaman', 'buku'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if (! $peminjaman->isPending()) {
            return redirect()->route('user.peminjaman.index')->with('error', 'Transaksi ini sudah diproses dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ]);

        if ((int) $validated['jumlah'] > $peminjaman->buku->stok) {
            return back()->withErrors([
                'jumlah' => 'Stok buku tidak mencukupi. Stok tersedia: ' . $peminjaman->buku->stok . ' buku.',
            ])->withInput();
        }

        $peminjaman->update($validated);

        return redirect()->route('user.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if (! $peminjaman->isPending()) {
            return redirect()->route('user.peminjaman.index')->with('error', 'Hanya transaksi menunggu yang dapat dihapus.');
        }

        $peminjaman->delete();

        return redirect()->route('user.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }

    public function return(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'approved') {
            return back()->with('error', 'Buku belum disetujui atau sudah dikembalikan.');
        }

        $peminjaman->buku->increment('stok', $peminjaman->jumlah);

        $peminjaman->update(['status' => 'returned']);

        return back()->with('success', 'Buku berhasil dikembalikan. Terima kasih!');
    }
}
