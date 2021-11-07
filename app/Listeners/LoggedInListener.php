<?php

namespace App\Listeners;

use App\Events\LoggedIn;
use App\Mail\NotifyOtp;
use App\Models\Device;
use App\Models\Otp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class LoggedInListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LoggedIn $event)
    {
        $otp = Otp::create([
            'user_id' => $event->user->id,
            'code' => Otp::rand()
        ]);
        $event->device->save();
        Mail::to($event->user)->send(new NotifyOtp($event->user, $otp));
    }
}
