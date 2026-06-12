<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $movements) {}

    public function collection()
    {
        return $this->movements;
    }

    public function headings(): array
    {
        return ['Produk', 'Cabang', 'Tipe', 'Jumlah', 'Catatan', 'Oleh', 'Tanggal'];
    }

    public function map($movement): array
    {
        return [
            $movement->product->name,
            $movement->branch->name,
            $movement->type,
            $movement->quantity,
            $movement->note,
            $movement->user->name,
            $movement->created_at->format('Y-m-d H:i'),
        ];
    }
}