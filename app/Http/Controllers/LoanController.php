<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $loans = Loan::with(['book', 'user'])
            ->when(! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->filled('status'), function ($q) use ($request) {
                match ($request->string('status')->toString()) {
                    'dipinjam' => $q->whereNull('returned_at')->whereDate('due_date', '>=', today()),
                    'terlambat' => $q->whereNull('returned_at')->whereDate('due_date', '<', today()),
                    'kembali' => $q->whereNotNull('returned_at'),
                    default => null,
                };
            })
            ->when($request->filled('search'), fn ($q) => $q->whereHas(
                'book', fn ($q2) => $q2->where('title', 'like', '%'.$request->string('search').'%')
            ))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $this->authorize('create', Loan::class);

        return view('loans.create', [
            'students' => User::where('role', 'siswa')->orderBy('name')->get(),
            'books' => Book::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Loan::class);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'borrowed_at' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:borrowed_at'],
        ]);

        $borrower = User::findOrFail($data['user_id']);
        if ($borrower->role !== 'siswa') {
            return back()->withErrors(['user_id' => 'Peminjam harus siswa.'])->withInput();
        }

        $book = Book::findOrFail($data['book_id']);
        if ($book->availableStock() < 1) {
            return back()->withErrors(['book_id' => 'Stok buku ini sedang habis (semua eksemplar dipinjam).'])->withInput();
        }

        $borrowedAt = $data['borrowed_at'] ?? today()->toDateString();
        $loan = Loan::create([
            'user_id' => $borrower->id,
            'book_id' => $book->id,
            'borrowed_at' => $borrowedAt,
            'due_date' => $data['due_date'] ?? today()->addDays(Loan::LOAN_DAYS)->toDateString(),
        ]);
        ActivityLog::record('loan.create', 'Peminjaman #'.$loan->id.': '.$book->title.' oleh '.$borrower->name);

        return redirect()->route('loans.show', $loan)->with('success', 'Peminjaman dicatat.');
    }

    public function show(Loan $loan)
    {
        $this->authorize('view', $loan); // anti-IDOR
        $loan->load(['book.category', 'user']);

        return view('loans.show', compact('loan'));
    }

    /** Proses pengembalian oleh staff: hitung & simpan denda keterlambatan. */
    public function returnBook(Loan $loan)
    {
        $this->authorize('update', $loan);

        if ($loan->isReturned()) {
            return back()->withErrors(['loan' => 'Buku ini sudah dikembalikan.']);
        }

        $loan->returned_at = today();
        $loan->fine_amount = $loan->lateDays() * Loan::FINE_PER_DAY;
        $loan->save();
        ActivityLog::record('loan.return', 'Pengembalian #'.$loan->id.', denda Rp'.number_format($loan->fine_amount, 0, ',', '.'));

        return back()->with('success', 'Buku dikembalikan. Denda keterlambatan: Rp'.number_format($loan->fine_amount, 0, ',', '.'));
    }
}
