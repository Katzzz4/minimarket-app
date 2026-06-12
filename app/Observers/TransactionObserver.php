<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => Transaction::class,
            'model_id' => $transaction->id,
            'new_values' => $transaction->toArray(),
        ]);
    }

    public function updated(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model_type' => Transaction::class,
            'model_id' => $transaction->id,
            'old_values' => $transaction->getOriginal(),
            'new_values' => $transaction->getChanges(),
        ]);
    }

    public function deleted(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model_type' => Transaction::class,
            'model_id' => $transaction->id,
            'old_values' => $transaction->toArray(),
        ]);
    }
}