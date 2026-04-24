@extends('layouts.portal')

@section('title', 'Periksa Kerusakan Buku')
@section('page_heading', 'Periksa Kerusakan Buku')
@section('page_description', 'Verifikasi kondisi buku yang dikembalikan')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h3>Pemeriksaan Kerusakan Buku</h3>
        </div>

        <div class="card-body" style="padding: 30px;">
            {{-- INFO PEMINJAMAN --}}
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <h4 class="font-bold mb-2">Informasi Peminjaman</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <small class="text-gray-600">Peminjam</small>
                        <p class="font-bold">{{ $peminjaman->user->name }}</p>
                    </div>
                    <div>
                        <small class="text-gray-600">Buku</small>
                        <p class="font-bold">{{ $peminjaman->buku->judul }}</p>
                    </div>
                    <div>
                        <small class="text-gray-600">Tanggal Kembali Seharusnya</small>
                        <p class="font-bold">{{ $peminjaman->tanggal_kembali->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <small class="text-gray-600">Tanggal Dikembalikan</small>
                        <p class="font-bold">{{ $pengembalian->tanggal_pengembalian->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- DENDA KETERLAMBATAN --}}
                @if ($pengembalian->denda > 0)
                    <div class="mt-3 p-2 bg-red-100 rounded border-l-2 border-red-500">
                        <small class="text-gray-700">Denda Keterlambatan:</small>
                        <p class="font-bold text-red-600">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</p>
                    </div>
                @else
                    <div class="mt-3 p-2 bg-green-100 rounded border-l-2 border-green-500">
                        <small class="text-gray-700">Tepat Waktu - Tidak Ada Denda Keterlambatan</small>
                    </div>
                @endif
            </div>

            {{-- FORM CEK KERUSAKAN --}}
            <form action="{{ route('admin.peminjaman.save-damage', $peminjaman) }}" method="POST">
                @csrf

                {{-- KERUSAKAN BUKU --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-3">Kondisi Buku</label>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="radio" id="baik" name="ada_kerusakan" value="0" checked 
                                   class="w-4 h-4 text-green-600" 
                                   onchange="toggleDeskripsi(false)">
                            <label for="baik" class="ml-3 flex items-center cursor-pointer">
                                <span class="text-lg">✓</span>
                                <span class="ml-2">Buku dalam kondisi BAIK - Tidak ada kerusakan</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="rusak" name="ada_kerusakan" value="1" 
                                   class="w-4 h-4 text-red-600" 
                                   onchange="toggleDeskripsi(true)">
                            <label for="rusak" class="ml-3 flex items-center cursor-pointer">
                                <span class="text-lg text-red-600">✗</span>
                                <span class="ml-2">Buku ADA KERUSAKAN - Perlu denda tambahan</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- DESKRIPSI KERUSAKAN --}}
                <div class="mb-6" id="deskripsi-section" style="display: none;">
                    <label for="deskripsi_kerusakan" class="block text-gray-700 font-bold mb-2">
                        Deskripsi Kerusakan *
                    </label>
                    <textarea 
                        name="deskripsi_kerusakan" 
                        id="deskripsi_kerusakan"
                        rows="4" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Jelaskan kerusakan yang terjadi pada buku..."
                    ></textarea>
                    @error('deskripsi_kerusakan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- INFO DENDA KERUSAKAN --}}
                <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <small class="text-gray-600">Denda Kerusakan Buku:</small>
                    <p class="font-bold text-yellow-600">Rp 50.000</p>
                    <small class="text-gray-500">*Akan ditambahkan ke total denda jika ada kerusakan</small>
                </div>

                {{-- PREVIEW TOTAL DENDA --}}
                <div class="mb-6 p-4 bg-gray-100 rounded-lg" id="total-denda-preview">
                    <small class="text-gray-600">Total Denda untuk Dibayar:</small>
                    <p class="font-bold text-lg" id="total-denda-text">
                        Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                    </p>
                </div>

                {{-- BUTTONS --}}
                <div class="flex gap-3 justify-end mt-8">
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        Simpan Pemeriksaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const baseDenda = {{ $pengembalian->denda }};
        const dendaKerusakan = 50000;

        function toggleDeskripsi(show) {
            const section = document.getElementById('deskripsi-section');
            const textarea = document.getElementById('deskripsi_kerusakan');
            
            section.style.display = show ? 'block' : 'none';
            
            // Update total denda preview
            const totalDenda = show ? baseDenda + dendaKerusakan : baseDenda;
            document.getElementById('total-denda-text').textContent = 
                'Rp ' + totalDenda.toLocaleString('id-ID');

            // Set required attribute
            if (show) {
                textarea.setAttribute('required', 'required');
            } else {
                textarea.removeAttribute('required');
                textarea.value = '';
            }
        }
    </script>
@endsection
