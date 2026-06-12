<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\ProductStock;

class ProductStockObserver
{
    public function created(ProductStock $productStock): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => ProductStock::class,
            'model_id' => $productStock->id,
            'new_values' => $productStock->toArray(),
        ]);
    }

    public function updated(ProductStock $productStock): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model_type' => ProductStock::class,
            'model_id' => $productStock->id,
            'old_values' => $productStock->getOriginal(),
            'new_values' => $productStock->getChanges(),
        ]);
    }

    public function deleted(ProductStock $productStock): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model_type' => ProductStock::class,
            'model_id' => $productStock->id,
            'old_values' => $productStock->toArray(),
        ]);
    }
}