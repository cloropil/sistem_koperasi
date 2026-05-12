<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Dokumen - {{ $dokumen->nama_dokumen }}</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid #ddd;
            z-index: 1000;
            padding: 10px;
            display: flex;
            gap: 10px;
        }
        .content {
            padding-top: 60px;
            height: calc(100vh - 60px);
        }
        iframe, img {
            width: 100%;
            height: 100%;
            border: none;
        }
        .fallback {
            padding: 1rem;
            font-family: Arial, sans-serif;
        }
        @media print {
            .toolbar {
                display: none !important;
            }
            body, html {
                margin: 0;
                padding: 0;
                height: auto;
            }
            .content {
                padding-top: 0;
                height: auto;
            }
            iframe, img {
                height: auto;
            }
        }
    </style>
    <script>
        window.addEventListener('load', function() {
            // Only auto-print if the document is previewable in-browser.
            var extension = '{{ strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION)) }}';
            if (['pdf','png','jpg','jpeg','gif'].includes(extension)) {
                setTimeout(function() { window.print(); }, 500);
            }
        });
    </script>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Print</button>
        <a href="{{ route('dokumen.index') }}">Kembali ke Daftar Dokumen</a>
        <a href="{{ route('dokumen.show', $dokumen->id) }}" target="_blank">Lihat Dokumen</a>
    </div>

    <div class="content">
        @php
            $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
        @endphp

        @if(in_array($extension, ['pdf']))
            <iframe src="{{ route('dokumen.show', $dokumen->id) }}"></iframe>
        @elseif(in_array($extension, ['png','jpg','jpeg','gif']))
            <img src="{{ route('dokumen.show', $dokumen->id) }}" alt="{{ $dokumen->nama_dokumen }}">
        @else
            <div class="fallback">
                <h2>Pratinjau tidak tersedia</h2>
                <p>Jenis file ini tidak dapat ditampilkan secara langsung. Silakan gunakan tombol "Lihat Dokumen" di atas atau unduh file terlebih dahulu.</p>
                <p><a href="{{ route('dokumen.show', $dokumen->id) }}" target="_blank">Buka / Unduh dokumen</a></p>
            </div>
        @endif
    </div>
</body>
</html>
