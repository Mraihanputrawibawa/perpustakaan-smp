<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    /** Denda per hari keterlambatan (Rupiah). */
    public const FINE_PER_DAY = 500;

    /** Lama peminjaman standar (hari). */
    public const LOAN_DAYS = 7;

    protected $fillable = [
        'user_id', 'book_id', 'borrowed_at', 'due_date', 'returned_at', 'fine_amount',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_date' => 'date',
            'returned_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }

    /** Terlambat jika belum kembali dan sudah melewati jatuh tempo, atau dikembalikan melewati jatuh tempo. */
    public function isLate(): bool
    {
        $end = $this->returned_at ?? today();

        return $end->gt($this->due_date);
    }

    public function lateDays(): int
    {
        if (! $this->isLate()) {
            return 0;
        }
        $end = $this->returned_at ?? today();

        return (int) $this->due_date->diffInDays($end);
    }

    /** Denda berjalan (untuk yang belum kembali) atau denda final yang tersimpan. */
    public function currentFine(): int
    {
        if ($this->isReturned()) {
            return $this->fine_amount;
        }

        return $this->lateDays() * self::FINE_PER_DAY;
    }

    public function statusLabel(): string
    {
        if ($this->isReturned()) {
            return $this->lateDays() > 0 ? 'Kembali (Terlambat)' : 'Kembali';
        }

        return $this->isLate() ? 'Terlambat' : 'Dipinjam';
    }
}
