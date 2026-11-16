<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'kelas'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // otomatis di-hash bcrypt oleh Laravel
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Pengelola perpustakaan. Hanya ada dua peran di aplikasi ini:
     * siswa dan admin — admin merangkap sebagai petugas perpustakaan
     * (mengelola buku, mencatat peminjaman/pengembalian, dan laporan).
     */
    public function isStaff(): bool
    {
        return $this->isAdmin();
    }
}
