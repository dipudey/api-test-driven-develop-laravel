<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_returned_by_sign_in_user()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/checkout/{$book->id}")
            ->assertOk();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/checkin/{$book->id}")
            ->assertOk();

        $this->assertCount(1, Reservation::query()->get());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    public function test_throw_exception_if_book_is_not_checked_out_first()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/checkin/{$book->id}")
            ->assertStatus(500);

        $this->assertCount(0, Reservation::query()->get());
    }

}
