<label>Judul Buku</label>
<input type="text" name="title" value="{{ old('title', $book->title ?? '') }}" required maxlength="200">
<label>Pengarang</label>
<input type="text" name="author" value="{{ old('author', $book->author ?? '') }}" required maxlength="150">
<label>Penerbit</label>
<input type="text" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}" maxlength="150">
<label>Tahun Terbit</label>
<input type="number" name="year" value="{{ old('year', $book->year ?? '') }}" min="1900" max="{{ date('Y') + 1 }}">
<label>ISBN</label>
<input type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" maxlength="20">
<label>Lokasi Rak</label>
<input type="text" name="shelf" value="{{ old('shelf', $book->shelf ?? '') }}" maxlength="20" placeholder="contoh: A1">
<label>Kategori</label>
<select name="category_id" required>
  @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $book->category_id ?? '')==$c->id)>{{ $c->name }}</option>@endforeach
</select>
<label>Stok (jumlah eksemplar)</label>
<input type="number" name="stock" value="{{ old('stock', $book->stock ?? 1) }}" min="0" required>
