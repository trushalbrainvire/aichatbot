<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\AfterAuthenticateJob;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class MerchantAuthObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('password')) { // Check if the password was updated

            AfterAuthenticateJob::dispatch(['user' => $user]);
        }
    }
}
