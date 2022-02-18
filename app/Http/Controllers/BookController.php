<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return view('books.index')->withBooks($books);
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'isbn' => 'required|digits:13',
            'title' => 'required',
            'description' => 'required'
        ]);

        $book = new Book();
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->save();

        return redirect()->route('books.show', $book);
    }

    public function show(Book $book)
    {
        return view('books.show')->withBook($book);
    }

    public function edit(Book $book)
    {
        return view('books.edit')->withBook($book);
    }

    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'isbn' => 'required|digits:13',
            'title' => 'required',
            'description' => 'required'
        ]);


        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->save();

        return redirect()->route('books.show', $book);
    }

    public function destroy(Book $book)
    {
        $reviews=$book->reviews;

        foreach($reviews as $review){
            $review->delete();
        }

        $book->delete();

        return redirect()->route('books.index');
    }
}
