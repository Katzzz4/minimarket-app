<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\StockMovement;

class StockMovementObserver
{
    public function created(StockMovement $stockMovement): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => StockMovement::class,
            'model_id' => $stockMovement->id,
            'new_values' => $stockMovement->toArray(),
        ]);
    }

    public function updated(StockMovement $stockMovement): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model_type' => StockMovement::class,
            'model_id' => $stockMovement->id,
            'old_values' => $stockMovement->getOriginal(),
            'new_values' => $stockMovement->getChanges(),
        ]);
    }

    public function deleted(StockMovement $stockMovement): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model_type' => StockMovement::class,
            'model_id' => $stockMovement->id,
            'old_values' => $stockMovement->toArray(),
        ]);
    }
}