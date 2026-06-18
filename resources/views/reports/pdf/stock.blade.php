<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h2 { font-size: 14px; margin-bottom: 4px; }
        p.sub { font-size: 10px; color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f3f4f6; text-align: left; padding: 6px 8px; font-size: 10px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; }
        .badge-in { color: #15803d; background: #dcfce7; padding: 2px 6px; border-radius: 9999px; }
        .badge-out { color: #b91c1c; background: #fee2e2; padding: 2px 6px; border-radius: 9999px; }
        .badge-adj { color: #374151; background: #f3f4f6; padding: 2px 6px; border-radius: 9999px; }
    </style>
</head>
<body>
    <h2>Laporan Stok Barang — JayuMart</h2>
    <p class="sub">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Cabang</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Petugas</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $m->product->name ?? '-' }}</td>
                <td>{{ $m->branch->name ?? '-' }}</td>
                <td>
                    @if($m->type === 'in')
                        <span class="badge-in">Masuk</span>
                    @elseif($m->type === 'out')
                        <span class="badge-out">Keluar</span>
                    @else
                        <span class="badge-adj">Adjustment</span>
                    @endif
                </td>
                <td>{{ $m->quantity }}</td>
                <td>{{ $m->note ?? '-' }}</td>
                <td>{{ $m->user->name ?? '-' }}</td>
                <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; color:#999; padding: 16px;">
                    Tidak ada data mutasi stok
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>