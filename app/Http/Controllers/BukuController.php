<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->orderBy('judul');

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

        $bukus = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::all();

        return view('admin.buku.index', compact('bukus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.buku.create', compact('kategoris'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'penulis' => 'required|string|max:255',
        'penerbit' => 'required|string|max:255',
        'tahun_terbit' => 'required|digits:4',
        'isbn' => 'nullable|string|unique:bukus,isbn',
        'stok' => 'required|integer|min:1',
        'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
        'kategori_id' => 'required|exists:kategoris,id',
        'image' => 'nullable|file'
    ]);

    // 🔥 HANDLE UPLOAD GAMBAR
    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $validated['image'] = $imageName;
    }

        Buku::create($validated);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'isbn' => 'nullable|string|unique:bukus,isbn,' . $buku->id,
            'stok' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku->update($validated);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diupdate!');
    }

    public function show(Buku $buku)
    {
        return view('admin.buku.show', compact('buku'));
    }

    public function destroy(Buku $buku)
    {
        if ($buku->peminjamans()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('admin.buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih sedang dipinjam.');
        }

        $buku->delete();
        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus!');
    }
}

