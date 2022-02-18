<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Book;
use App\Models\Review;

class  ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        $reviews = $book->reviews;

        return view('reviews.index')->withBook($book)->withReviews($reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Book $book)
    {
        return view('reviews.create')->withBook($book);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewRequest $request, Book $book)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $review = new Review();
        $review->book_id = $book->id;
        $review->title = $request->title;
        $review->description = $request->description;
        $review->save();

        return redirect()->route('books.reviews.show', ['book' => $book, 'review' => $review]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book, Review $review)
    {
        return view('reviews.show')->withBook($book)->withReview($review);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book, Review $review)
    {
        return view('reviews.edit')->with("book", $book)->with("review", $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReviewRequest  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Book $book, Review $review)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $review->title = $request->title;
        $review->description = $request->description;
        $review->save();

        return redirect()->route('books.reviews.show', ['book' => $book, 'review' => $review]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book, Review $review)
    {
        $review->delete();

        return redirect()->route('books.reviews.index', $book);
    }
}
