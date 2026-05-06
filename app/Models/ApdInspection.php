<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ApdInspection extends Model
{
    protected $fillable = [
        'bulan',
        'tanggal_inspeksi',
        'user_id',
        'catatan',
        'status',
        'stock_updated',
    ];

    protected $casts = [
        'tanggal_inspeksi' => 'date',
        'stock_updated' => 'boolean',
    ];

    /**
     * Get the month key (YYYY-MM format) for uniqueness
     */
    public function getMonthKeyAttribute(): string
    {
        return $this->bulan;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(ApdInspectionDetail::class);
    }

    // Relationship to stock adjustments made during this inspection
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'apd_inspection_id');
    }

    // Scope: inspections by month
    public function scopeByMonth(Builder $query, string $month): Builder
    {
        return $query->where('bulan', $month);
    }

    // Scope: final inspections only
    public function scopeFinal(Builder $query): Builder
    {
        return $this->where('status', 'final');
    }

    /**
     * Boot the model and its events.
     */
    protected static function booted(): void
    {
        static::creating(function ($inspection) {
            if (empty($inspection->user_id)) {
                $inspection->user_id = auth()->id() ?? 1; // fallback to admin
            }
        });

        static::updated(function ($inspection) {
            if ($inspection->isDirty('status')
                && $inspection->status === 'final'
                && !$inspection->stock_updated) {
                // Process stock reduction for unfit items
                \App\Helpers\ApdInspectionHelper::finalizeInspection($inspection);
                // Mark as stock updated without triggering another updated event
                $inspection->stock_updated = true;
                $inspection->saveQuietly();
            }
        });
    }
}
