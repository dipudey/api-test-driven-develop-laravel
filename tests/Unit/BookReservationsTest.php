<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use App\Exceptions\BookNotCheckedOutException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_checkout()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::query()->get());
        $this->assertEquals($user->id, Reservation::query()->first()->user_id);
        $this->assertEquals($book->id, Reservation::query()->first()->book_id);
        $this->assertEquals(now(), Reservation::query()->first()->checked_out_at);
    }

    public function test_book_can_be_returned()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, Reservation::query()->get());
        $this->assertEquals($user->id, Reservation::query()->first()->user_id);
        $this->assertEquals($book->id, Reservation::query()->first()->book_id);
        $this->assertEquals(now(), Reservation::query()->first()->checked_in_at);
    }

    public function test_if_not_checked_out_exception_is_throw()
    {
        $this->expectException(BookNotCheckedOutException::class);

        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkin($user);

    }
}
