<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi_{{ $nama }}_{{ request('startDate') ? \Carbon\Carbon::parse(request('startDate'))->format('Y-m') : 'All' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background-color: white !important;
            }
            .print-hidden {
                display: none !important;
            }
            .document-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: none !important;
            }
        }
        .table-print th, .table-print td {
            border: 1px solid black;
            padding: 8px 12px;
            font-size: 12px;
        }
        .table-print th {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 text-black font-sans antialiased min-h-screen py-10 relative">
    
    <!-- Action Bar -->
    <div class="fixed top-4 right-4 z-50 print-hidden flex gap-3">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow font-medium hover:bg-indigo-700 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow font-medium hover:bg-gray-700 text-sm">
            Tutup
        </button>
    </div>

    <!-- Document -->
    <div class="document-container max-w-4xl mx-auto bg-white shadow-xl p-10 sm:p-16">
        
        <div class="text-center mb-8">
            <h1 class="text-xl font-bold mb-1">Presensi Kehadiran Student Staff Unit PUTI</h1>
            <h2 class="text-lg font-bold mb-4">Bulan ({{ $formattedStartDate }} – {{ $formattedEndDate }})</h2>
            <p class="font-bold text-sm">Nama: {{ $nama }}</p>
        </div>

        <table class="w-full table-print border-collapse mb-12">
            <thead>
                <tr>
                    <th class="w-12">No</th>
                    <th class="w-40">Tanggal</th>
                    <th class="w-40">Pukul</th>
                    <th class="w-24">Durasi</th>
                    <th>Kegiatan</th>
                    <th class="w-24">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formattedData as $index => $d)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $d->tanggal }}</td>
                    <td class="text-center">{{ $d->waktu }}</td>
                    <td class="text-center">{{ $d->durasi }}</td>
                    <td>{{ $d->pekerjaan }}</td>
                    <td></td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-center font-bold">Total</td>
                    <td class="text-center font-bold">{{ $totalJam }} Jam</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-center mt-12">
            <div class="text-center">
                <p class="mb-20 text-sm">Kaur. Pusat Teknologi dan Informasi</p>
                <p class="font-bold text-sm underline">(Selfina Anggraini)</p>
            </div>
        </div>
    </div>
</body>
</html>
