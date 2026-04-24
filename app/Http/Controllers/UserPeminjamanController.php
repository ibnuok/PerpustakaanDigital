<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pengembalian;
use Carbon\Carbon;

class UserPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()
            ->peminjamans()
            ->with(['buku.kategori', 'pengembalian'])
            ->latest();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('penulis', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTER TANGGAL
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
        $query = Buku::with('kategori');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('penulis', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }

        // FILTER
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // HANYA STOK TERSEDIA
        $query->where('stok', '>', 0);

        $bukus = $query->orderBy('judul')->paginate(12)->withQueryString();
        $kategoris = Kategori::all();

        return view('user.bukus', compact('bukus', 'kategoris'));
    }

    public function create(Request $request)
    {
        $buku_id = $request->query('buku_id');

        // ✅ Redirect ke halaman buku jika buku_id tidak ada, bukan abort 404
        if (!$buku_id) {
            return redirect()->route('user.bukus')->with('error', 'Pilih buku terlebih dahulu.');
        }

        $buku = Buku::where('stok', '>', 0)->findOrFail($buku_id);

        return view('user.peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'jam_pinjam' => 'required',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jam_kembali' => 'required',
        ]);

        DB::transaction(function () use ($data) {

            $buku = Buku::lockForUpdate()->findOrFail($data['buku_id']);

            if ($data['jumlah'] > $buku->stok) {
                throw new \Exception('Stok tidak cukup');
            }

            $tanggal_pinjam = Carbon::parse($data['tanggal_pinjam'] . ' ' . $data['jam_pinjam']);
            $tanggal_kembali = Carbon::parse($data['tanggal_kembali'] . ' ' . $data['jam_kembali']);

            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $data['buku_id'],
                'jumlah' => $data['jumlah'],
                'tanggal_pinjam' => $tanggal_pinjam,
                'tanggal_kembali' => $tanggal_kembali,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('user.peminjaman.index')
            ->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function edit(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa diedit');
        }

        $buku = $peminjaman->buku;

        return view('user.peminjaman.edit', compact('peminjaman', 'buku'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa diubah');
        }

        $data = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'jam_pinjam' => 'required',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jam_kembali' => 'required',
        ]);

        $tanggal_pinjam = Carbon::parse($data['tanggal_pinjam'] . ' ' . $data['jam_pinjam']);
        $tanggal_kembali = Carbon::parse($data['tanggal_kembali'] . ' ' . $data['jam_kembali']);

        $peminjaman->update([
            'jumlah' => $data['jumlah'],
            'tanggal_pinjam' => $tanggal_pinjam,
            'tanggal_kembali' => $tanggal_kembali,
        ]);

        return redirect()
            ->route('user.peminjaman.index')
            ->with('success', 'Berhasil diupdate');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Tidak bisa dihapus');
        }

        $peminjaman->delete();

        return back()->with('success', 'Berhasil dihapus');
    }

    public function return(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id(), 403);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku belum dipinjam');
        }

        DB::transaction(function () use ($peminjaman) {

            $now = now();

            $peminjaman->buku->increment('stok', $peminjaman->jumlah);

            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'tanggal_pengembalian' => $now,
                    'denda' => $peminjaman->calculateFine($now),
                ]
            );

            $peminjaman->update([
                'status' => 'returned'
            ]);
        });

        return back()->with('success', 'Buku berhasil dikembalikan');
    }
}