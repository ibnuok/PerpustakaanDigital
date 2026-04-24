@extends('layouts.portal')

@section('title', 'Bayar Denda')
@section('page_heading', 'Pembayaran Denda')
@section('page_description', 'Unggah bukti pembayaran denda Anda')

@section('content')
    <style>
        .elegant-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .elegant-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
        }

        .card-body-elegant {
            padding: 24px;
        }

        .info-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .info-item {
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
        }

        .denda-amount {
            font-size: 1.875rem;
            font-weight: bold;
            color: #dc2626;
        }

        .instruction-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            padding: 16px;
            border-radius: 8px;
            margin: 16px 0;
        }

        .bank-info {
            background: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 8px;
            border: 2px solid #3b82f6;
        }

        .bank-detail {
            font-size: 0.95rem;
            margin: 4px 0;
        }

        .upload-zone {
            border: 2px dashed #9ca3af;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            border-color: #667eea;
            background-color: #f9fafb;
        }

        .upload-zone.dragging {
            border-color: #667eea;
            background-color: #eef2ff;
        }

        .file-input-hidden {
            display: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .button-group a, .button-group button {
            flex: 1;
        }

        .file-name {
            color: #10b981;
            font-weight: 600;
            margin-top: 8px;
        }

        .warning-box {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            padding: 16px;
            border-radius: 8px;
            margin: 16px 0;
        }

        .warning-text {
            color: #7f1d1d;
            font-size: 0.95rem;
        }
    </style>

    <div class="elegant-container">
        {{-- DENDA INFO --}}
        <div class="elegant-card">
            <div class="card-header-gradient">
                <h3>💰 Detail Pembayaran Denda</h3>
            </div>
            <div class="card-body-elegant">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Buku</div>
                        <div class="info-value">{{ $pengembalian->peminjaman->buku->judul }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total Denda</div>
                        <div class="denda-amount">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="info-box">
                    <div style="margin-bottom: 8px;">
                        <b style="color: #92400e;">📌 Rincian Denda:</b>
                    </div>
                    <ul style="list-style: none; padding: 0; color: #78350f; font-size: 0.95rem;">
                        @if ($pengembalian->ada_kerusakan)
                            <li style="margin-bottom: 4px;">🔴 Kerusakan Buku: Rp 50.000</li>
                        @endif
                        @php
                            $dendaKeterlambatan = $pengembalian->denda - ($pengembalian->ada_kerusakan ? 50000 : 0);
                        @endphp
                        @if ($dendaKeterlambatan > 0)
                            <li>🔴 Keterlambatan Pengembalian: Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</li>
                        @endif
                    </ul>

                    @if ($pengembalian->deskripsi_kerusakan)
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.1);">
                            <div class="info-label">Keterangan:</div>
                            <p style="color: #78350f; margin-top: 4px;">{{ $pengembalian->deskripsi_kerusakan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- INSTRUKSI PEMBAYARAN --}}
        <div class="elegant-card" style="margin-top: 20px;">
            <div class="card-header-gradient">
                <h3>🏦 Instruksi Pembayaran</h3>
            </div>
            <div class="card-body-elegant">
                <div class="instruction-box">
                    <p style="color: #1e40af; margin-bottom: 8px;">
                        <b>Silakan transfer denda ke rekening perpustakaan:</b>
                    </p>
                    <div class="bank-info">
                        <div class="bank-detail"><b>Bank</b>: BCA</div>
                        <div class="bank-detail"><b>No. Rekening</b>: 1234567890</div>
                        <div class="bank-detail"><b>Atas Nama</b>: Perpustakaan</div>
                    </div>
                </div>

                <p style="color: #475569; font-size: 0.95rem; line-height: 1.6;">
                    📸 Setelah melakukan transfer, silakan unggah bukti pembayaran (screenshot atau foto) di bawah ini. 
                    Admin akan memverifikasi dalam waktu <b>maksimal 1x24 jam</b>.
                </p>
            </div>
        </div>

        {{-- UPLOAD BUKTI --}}
        <div class="elegant-card" style="margin-top: 20px;">
            <div class="card-header-gradient">
                <h3>📤 Unggah Bukti Pembayaran</h3>
            </div>
            <div class="card-body-elegant">
                <form action="{{ route('user.denda.submit-payment', $pengembalian) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="upload-zone" id="drop-zone">
                        <input 
                            type="file" 
                            name="bukti_pembayaran" 
                            id="bukti_pembayaran"
                            accept="image/*"
                            class="file-input-hidden"
                            required
                            onchange="updateFileName(this)"
                        >
                        
                        <svg class="w-16 h-16 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 12px; color: #9ca3af;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        
                        <p style="color: #374151; margin-bottom: 4px;">
                            <strong>Klik di sini untuk memilih file</strong>
                        </p>
                        <p style="color: #6b7280; margin-bottom: 8px;">atau drag and drop gambar ke sini</p>
                        <p style="color: #9ca3af; font-size: 0.875rem;">
                            Format: JPG, PNG, GIF | Ukuran maksimal: 2MB
                        </p>
                        
                        <div id="file-name" class="file-name" style="display: none;"></div>
                    </div>
                    
                    @error('bukti_pembayaran')
                        <p style="color: #dc2626; font-size: 0.875rem; margin-top: 8px;">❌ {{ $message }}</p>
                    @enderror

                    <div class="warning-box">
                        <p class="warning-text">
                            <b>⚠️ Perhatian:</b> Pastikan bukti pembayaran jelas, terlihat dengan baik, dan menampilkan 
                            nomor rekening tujuan serta nominal transfer yang sesuai dengan denda.
                        </p>
                    </div>

                    <div class="button-group">
                        <a href="{{ route('user.denda.index') }}" class="btn-cancel">
                            ← Batal
                        </a>
                        <button type="submit" class="btn-submit">
                            ✓ Submit Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('bukti_pembayaran');

        // Click to open file picker
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('dragging');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('dragging');
            }, false);
        });

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            updateFileName(fileInput);
        }

        function updateFileName(input) {
            const fileName = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileName.textContent = '✓ ' + input.files[0].name;
                fileName.style.display = 'block';
            }
        }
    </script>
@endsection
