<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Anti-IDOR: siswa hanya boleh melihat peminjamannya sendiri.
     * Staff (admin/petugas) boleh melihat semua peminjaman.
     */
    public function view(User $user, Loan $loan): bool
    {
        return $user->isStaff() || $loan->user_id === $user->id;
    }

    /** Hanya staff yang boleh mencatat peminjaman & pengembalian. */
    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Loan $loan): bool
    {
        return $user->isStaff();
    }
}
