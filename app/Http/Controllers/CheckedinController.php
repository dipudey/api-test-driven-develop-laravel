<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CheckedinController extends Controller
{
    public function checkedin(Request $request, $bookId)
    {
        $book = Book::query()->find($bookId);

        if (!$book) {
            throw new \Exception("Book Not Found");
        }

        $book->checkin(auth()->user());
    }
}
