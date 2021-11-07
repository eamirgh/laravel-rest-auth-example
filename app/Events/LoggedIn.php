<?php

namespace App\Events;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoggedIn
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public Device $device)
    {
    }
}
