<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->withCount(['loans as dipinjam_count' => fn ($q) => $q->whereNull('returned_at')])
            ->when($request->filled('search'), fn ($q) => $q->where(
                fn ($q2) => $q2->where('title', 'like', '%'.$request->string('search').'%')
                    ->orWhere('author', 'like', '%'.$request->string('search').'%')
                    ->orWhere('isbn', 'like', '%'.$request->string('search').'%')
            ))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('books.index', [
            'books' => $books,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Book::class);

        return view('books.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'author' => ['required', 'string', 'max:150'],
            'publisher' => ['nullable', 'string', 'max:150'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books,isbn'],
            'shelf' => ['nullable', 'string', 'max:20'],
            'stock' => ['required', 'integer', 'min:0', 'max:10000'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $book = Book::create($data);
        ActivityLog::record('book.create', 'Buku: '.$book->title);

        return redirect()->route('books.index')->with('success', 'Buku ditambahkan ke katalog.');
    }

    public function show(Book $book)
    {
        $book->load('category');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        return view('books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'author' => ['required', 'string', 'max:150'],
            'publisher' => ['nullable', 'string', 'max:150'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books,isbn,'.$book->id],
            'shelf' => ['nullable', 'string', 'max:20'],
            'stock' => ['required', 'integer', 'min:0', 'max:10000'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        if ($data['stock'] < $book->activeLoansCount()) {
            return back()->withErrors(['stock' => 'Stok tidak boleh lebih kecil dari jumlah yang sedang dipinjam ('.$book->activeLoansCount().').'])->withInput();
        }

        $book->update($data);
        ActivityLog::record('book.update', 'Buku: '.$book->title);

        return redirect()->route('books.index')->with('success', 'Buku diperbarui.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        if ($book->loans()->whereNull('returned_at')->exists()) {
            return back()->withErrors(['book' => 'Buku masih ada yang dipinjam dan tidak dapat dihapus.']);
        }
        $book->delete();
        ActivityLog::record('book.delete', 'Buku: '.$book->title);

        return redirect()->route('books.index')->with('success', 'Buku dihapus dari katalog.');
    }
}
