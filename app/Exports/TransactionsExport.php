<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $transactions) {}

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return ['Invoice', 'Cabang', 'Kasir', 'Total', 'Dibayar', 'Kembalian', 'Tanggal'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->branch->name,
            $transaction->user->name,
            $transaction->total,
            $transaction->paid,
            $transaction->change,
            $transaction->created_at->format('Y-m-d H:i'),
        ];
    }
}