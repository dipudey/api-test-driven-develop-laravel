<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
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

    public function test_title_is_required()
    {
        $this->postJson('/api/book', [
            "title"  => "",
            "author" => "Test"
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    public function test_author_is_required()
    {
        $this->postJson('/api/book', [
            "title"  => "Code With Fun",
            "author" => ""
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('author');
    }

    public function test_book_can_be_updated_library()
    {
        $this->withoutExceptionHandling();

        $this->postJson('/api/book', [
            "title"  => "Code With Fun",
            "author" => "Test"
        ])
            ->assertOk();

        $book = Book::query()->first();

        $this->patchJson("/api/book/{$book->id}", [
            "title"  => "Coding With Fun",
            "author" => "Test"
        ])
            ->assertOk();

        $this->assertEquals("Coding With Fun", Book::query()->first()->title);
        $this->assertEquals("Test", Book::query()->first()->author);
    }

    public function test_book_can_be_delete()
    {
        $this->postJson('/api/book', [
            "title"  => "Code With Fun",
            "author" => "Test"
        ])
            ->assertOk();

        $book = Book::query()->first();

        $this->deleteJson("/api/book/{$book->id}")
            ->assertOk();

        $this->assertCount(0, Book::query()->get());
    }
}
