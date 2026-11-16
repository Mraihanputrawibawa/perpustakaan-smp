<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'category_id', 'title', 'author', 'publisher', 'year', 'isbn', 'shelf', 'stock',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /** Jumlah eksemplar yang sedang dipinjam (belum kembali). */
    public function activeLoansCount(): int
    {
        return $this->loans()->whereNull('returned_at')->count();
    }

    /** Stok yang tersedia untuk dipinjam saat ini. */
    public function availableStock(): int
    {
        return max(0, $this->stock - $this->activeLoansCount());
    }
}
