<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_checked_out_by_sign_in_user()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/checkout/{$book->id}")
            ->assertOk();

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function test_book_is_exist_on_library()
    {

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->postJson("/api/checkout/" . 120)
            ->assertStatus(500);

        $this->assertCount(0, Reservation::query()->get());
    }

    public function test_can_only_sign_in_user_checked_out_book()
    {
        $book = Book::factory()->create();
        $this->postJson("/api/checkout/{$book->id}")
            ->assertUnauthorized();

        $this->assertCount(0, Reservation::query()->get());
    }
}
