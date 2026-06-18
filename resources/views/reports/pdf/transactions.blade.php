<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h2 { font-size: 14px; margin-bottom: 4px; }
        p.sub { font-size: 10px; color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f3f4f6; text-align: left; padding: 6px 8px; font-size: 10px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; border-top: 2px solid #e5e7eb; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi — JayuMart</h2>
    <p class="sub">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Cabang</th>
                <th class="text-right">Total</th>
                <th class="text-right">Dibayar</th>
                <th class="text-right">Kembalian</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->invoice_number }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td>{{ $t->branch->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($t->paid, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($t->change, 0, ',', '.') }}</td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; color:#999; padding: 16px;">
                    Tidak ada data transaksi
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total Keseluruhan</td>
                <td class="text-right">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>