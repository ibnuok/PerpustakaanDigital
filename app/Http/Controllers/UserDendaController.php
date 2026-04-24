<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserDendaController extends Controller
{
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
            ->sum(fn ($p) => $p->pengembalian->denda ?? 0);

        return view('user.denda.index', compact('pengembalians', 'totalDenda'));
    }

    public function show(Pengembalian $pengembalian)
    {
        $this->authorizePengembalian($pengembalian);

        if ($pengembalian->denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar!');
        }

        return view('user.denda.show', compact('pengembalian'));
    }

    public function paymentForm(Pengembalian $pengembalian)
    {
        $this->authorizePengembalian($pengembalian);

        if ($pengembalian->denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar!');
        }

        if ($pengembalian->status_pembayaran === 'sudah_dibayar') {
            return back()->with('error', 'Denda sudah dibayar!');
        }

        return view('user.denda.payment', compact('pengembalian'));
    }

    public function submitPayment(Request $request, Pengembalian $pengembalian)
    {
        $this->authorizePengembalian($pengembalian);

        if ($pengembalian->denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar!');
        }

        if ($pengembalian->status_pembayaran === 'sudah_dibayar') {
            return back()->with('error', 'Denda sudah lunas!');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'transfer'])],
            'bukti_pembayaran' => [
                Rule::requiredIf(fn () => $request->input('metode_pembayaran') === 'transfer'),
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload untuk metode transfer.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpg, jpeg, png, gif, atau webp.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $filename = $pengembalian->bukti_pembayaran;

        if ($validated['metode_pembayaran'] === 'transfer') {
            if ($pengembalian->bukti_pembayaran) {
                Storage::delete('public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);
            }

            $file = $request->file('bukti_pembayaran');
            $filename = 'pembayaran_' . $pengembalian->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/bukti_pembayaran', $filename);
        } elseif ($pengembalian->bukti_pembayaran) {
            Storage::delete('public/bukti_pembayaran/' . $pengembalian->bukti_pembayaran);
            $filename = null;
        }

        $pengembalian->update([
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $validated['metode_pembayaran'] === 'transfer' ? $filename : null,
            'status_pembayaran' => 'pending_approval',
            'tanggal_pembayaran' => now(),
            'catatan_penolakan' => null,
        ]);

        return redirect()->route('user.denda.index')
            ->with('success', $validated['metode_pembayaran'] === 'tunai'
                ? 'Pengajuan pembayaran tunai berhasil dikirim. Menunggu verifikasi admin.'
                : 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

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

    protected function authorizePengembalian(Pengembalian $pengembalian): void
    {
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
}
