<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\Book;
use App\Models\Language\Language;
use App\Models\Bible\Text;
use App\Transformers\BooksTransformer;
use Illuminate\View\View;

class BooksController extends APIController
{

	/**
	 *
	 *
	 * @return JSON|View
	 */
	public function index()
	{
		if($this->api) {
			return \Cache::remember('v4_books_index', 2400, function() {
				$books = Book::with('codes')->orderBy('book_order')->get();
				return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
			});
		}

		return view('docs.books');
	}


	/**
	 * This Function handles the "Book Order Listing" Route on V2 and the "books" route on V4
	 * Gets the book order and code listing for a volume.
	 * REST URL: http://dbt.io/library/bookorder
	 *
	 * @return JSON|View
	 */
	public function show()
    {
    	if(!$this->api) return view('docs.v2.books.BookOrderListing');

		$abbreviation = checkParam('dam_id');
		$books = BibleBook::with('book')->where('bible_id',$abbreviation)->get()->sortBy('book.book_order');
		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer())->serializeWith($this->serializer)->toArray());
    }

	/**
	 * This function handles the "Book Name Listing" route on Version 2 of the DBP
	 * This will retrieve the native language book names for a DBP language code.
	 * OLD REST URL: http://dbt.io/library/bookname
	 *
	 * @return View|JSON
	 */
	public function bookNames()
    {
    	if(!$this->api) return view('docs.books.bookNames');

		$languageCode = checkParam('language_code');
		$language = fetchLanguage($languageCode);

		// Fetch Bible Book Names By Bible Iso and Order by Book Order
		$books = BibleBook::whereHas('bible', function($q) use ($language) {$q->where('iso', '=', $language->iso);})
			->select('book_id','bible_books.name','books.book_order')
			->join('books', 'books.id', '=', 'bible_books.book_id')
			->orderBy('books.book_order', 'ASC')->distinct()->get();

		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer()));
    }

	/**
	 * Supports V2:
	 *
	 * This Function handles the "Chapter Listing" route on Version 2 of the DBP
	 * This lists the chapters for a book or all books in a standard bible volume.
	 * Story volumes in DBP are defined in the same top down fashion as standard bibles.
	 * So the first partitioning is into books, which correspond to the segments of audio or video.
	 * So story volumes have no chapters.
	 * OLD REST URL: http://dbt.io/library/chapter
	 *
	 * @return JSON|View
	 */
	public function chapters()
    {
	    if($this->api) {
		    $bible_id = checkParam('dam_id');
		    $book_id = checkParam('book_id');
		    $chapters = Text::where('bible_id',$bible_id)->Where('book_id',$book_id)->select('chapter_number','bible_id','book_id')->distinct()->orderBy('chapter_number')->get();
		    return $this->reply(fractal()->collection($chapters)->transformWith(new BooksTransformer()));
	    }
		return view('docs.books.chapters');
    }

}
