<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->isStaff()) {
            $stats = [
                'total_buku' => Book::count(),
                'total_eksemplar' => (int) Book::sum('stock'),
                'dipinjam' => Loan::whereNull('returned_at')->count(),
                'terlambat' => Loan::whereNull('returned_at')->whereDate('due_date', '<', today())->count(),
                'kembali' => Loan::whereNotNull('returned_at')->count(),
                'total_denda' => (int) Loan::sum('fine_amount'),
            ];
            $latest = Loan::with(['book', 'user'])->latest()->take(5)->get();
        } else {
            $my = Loan::where('user_id', $user->id);
            $stats = [
                'total_buku' => Book::count(),
                'total_eksemplar' => (int) Book::sum('stock'),
                'dipinjam' => (clone $my)->whereNull('returned_at')->count(),
                'terlambat' => (clone $my)->whereNull('returned_at')->whereDate('due_date', '<', today())->count(),
                'kembali' => (clone $my)->whereNotNull('returned_at')->count(),
                'total_denda' => (int) (clone $my)->sum('fine_amount'),
            ];
            $latest = (clone $my)->with(['book', 'user'])->latest()->take(5)->get();
        }

        $popularBooks = Book::with('category')
            ->withCount('loans')
            ->withCount(['loans as active_loans_count' => fn ($q) => $q->whereNull('returned_at')])
            ->orderByDesc('loans_count')
            ->orderBy('title')
            ->take(4)
            ->get();

        return view('dashboard', compact('stats', 'latest', 'popularBooks'));
    }
}
