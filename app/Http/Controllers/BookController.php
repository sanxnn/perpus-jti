<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::latest()->get();
        return view('books', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'nullable|unique:books',
            'stock' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'nullable|unique:books,isbn,' . $book->id,
            'stock' => 'required|integer|min:1',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->back()->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Buku berhasil dihapus.');
    }
}
