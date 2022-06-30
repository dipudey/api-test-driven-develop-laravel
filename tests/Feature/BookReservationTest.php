<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_book_can_be_added_library()
    {
        $this->withoutExceptionHandling();

        $this->postJson('/api/book', [
            "title"  => "Code With Fun",
            "author" => "Test"
        ])
            ->assertOk();

        $this->assertCount(1, Book::all());
    }
}
