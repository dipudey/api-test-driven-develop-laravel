<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Store Book in a library
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        Book::query()
            ->create($this->validateRequest($request));
    }

    /**
     * Book updated
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        Book::query()
            ->find($id)
            ->update($this->validateRequest($request));
    }

    /**
     * Delete a Book  from library
     *
     * @param $id
     */
    public function destroy($id)
    {
        Book::query()
            ->find($id)
            ->delete();
    }

    /**
     * Request Validation
     *
     * @param Request $request
     *
     * @return array
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'title'  => 'required',
            'author' => 'required'
        ]);
    }
}
