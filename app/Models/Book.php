<?php

namespace App\Models;

use App\Exceptions\BookNotCheckedOutException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Checked out a book
     *
     * @param User $user
     */
    public function checkout(User $user)
    {
        $this->reservations()->create([
            'user_id'        => $user->id,
            'checked_out_at' => now()
        ]);
    }

    /**
     * Return a book
     *
     * @param User $user
     *
     * @throws BookNotCheckedOutException
     */
    public function checkin(User $user)
    {
        $reservation = $this->reservations()
            ->where("user_id", $user->id)
            ->whereNotNull("checked_out_at")
            ->whereNull("checked_in_at")
            ->first();

        if (!$reservation) {
            throw new BookNotCheckedOutException("Book Not Checked In");
        }

        $reservation->update([
            'checked_in_at' => now()
        ]);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
