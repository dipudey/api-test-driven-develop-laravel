<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CheckedoutController extends Controller
{
    public function checkedout(Request $request, $bookId)
    {
        $book = Book::query()->find($bookId);

        if (!$book) {
            throw new \Exception("Book Not Found");
        }

        $book->checkout(auth()->user());
    }
}
