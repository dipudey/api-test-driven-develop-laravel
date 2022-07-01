<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
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
}
