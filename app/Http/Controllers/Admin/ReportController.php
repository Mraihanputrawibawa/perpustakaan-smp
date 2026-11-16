<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private function filtered(Request $request)
    {
        return Loan::with(['user', 'book.category'])
            ->when($request->filled('from'), fn ($q) => $q->whereDate('loans.borrowed_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('loans.borrowed_at', '<=', $request->date('to')))
            ->latest('loans.borrowed_at');
    }

    public function index(Request $request)
    {
        $base = $this->filtered($request);

        $summary = [
            'total' => (clone $base)->count(),
            'dipinjam' => (clone $base)->whereNull('returned_at')->count(),
            'terlambat' => (clone $base)->whereNull('returned_at')->whereDate('due_date', '<', today())->count(),
            'total_denda' => (int) (clone $base)->sum('fine_amount'),
            'by_category' => (clone $base)->join('books', 'books.id', '=', 'loans.book_id')
                ->join('categories', 'categories.id', '=', 'books.category_id')
                ->selectRaw('categories.name, count(*) as total')->groupBy('categories.name')->pluck('total', 'categories.name'),
            'top_books' => (clone $base)->join('books', 'books.id', '=', 'loans.book_id')
                ->selectRaw('books.title, count(*) as total')->groupBy('books.title')
                ->orderByDesc('total')->limit(5)->pluck('total', 'books.title'),
        ];

        $loans = (clone $base)->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('summary', 'loans'));
    }

    /** Ekspor laporan peminjaman ke CSV (dibuka di Excel). */
    public function exportCsv(Request $request): StreamedResponse
    {
        $loans = $this->filtered($request)->get();

        return response()->streamDownload(function () use ($loans) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Siswa', 'Kelas', 'Buku', 'Kategori', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status', 'Denda (Rp)']);
            foreach ($loans as $l) {
                fputcsv($out, [
                    $l->id, $l->user->name, $l->user->kelas, $l->book->title, $l->book->category->name,
                    $l->borrowed_at->toDateString(), $l->due_date->toDateString(), $l->returned_at?->toDateString(),
                    $l->statusLabel(), $l->currentFine(),
                ]);
            }
            fclose($out);
        }, 'laporan-perpustakaan.csv', ['Content-Type' => 'text/csv']);
    }

    /** Halaman ramah-cetak: simpan sebagai PDF dari browser (Ctrl+P -> Save as PDF). */
    public function print(Request $request)
    {
        $loans = $this->filtered($request)->get();

        return view('admin.reports.print', compact('loans'));
    }
}
