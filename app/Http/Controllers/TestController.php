<?php

namespace App\Http\Controllers;

use App\Creditpack;
use App\Mail\Contact;
use App\Mail\Welcome;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function storeUserFavorite(int $userId, int $favoriteId)
    {
        $key = 'users.favorites.' . $userId;

        return Redis::sadd($key, $favoriteId);
    }

    public function retrieveUserFavorites(int $userId)
    {
        return Redis::smembers('users.favorites.' . $userId);
    }

    public function deleteUserFavorite(int $userId, int $favoriteId)
    {
        return Redis::srem('users.favorites.' . $userId, [$favoriteId]);
    }

    public function showWelcomeEmail()
    {
        $user = User::find(2);

        return view('emails.welcome', [
            'user' => $user
        ]);
    }

    public function showCreditsBoughtEmail()
    {
        $user = User::find(2);

        $creditPack = Creditpack::find(2);

        return view('emails.credits-bought', [
            'user' => $user,
            'creditPack' => $creditPack
        ]);
    }

    public function showDeactivatedEmail()
    {
        $user = User::find(2);

        return view('emails.deactivated', [
            'user' => $user
        ]);
    }

    public function showMessageReceivedEmail()
    {
        $sender = User::find(3);
        $recipient = User::find(4);

        return view('emails.message-received', [
            'user' => $sender,
            'messageSender' => $sender,
            'messageRecipient' => $recipient,
            'carbonNow' => Carbon::now()
        ]);
    }

    public function showProfileViewedEmail()
    {
        $sender = User::find(3);
        $recipient = User::find(4);

        return view('emails.profile-viewed', [
            'user' => $sender,
            'messageSender' => $sender,
            'messageRecipient' => $recipient,
            'carbonNow' => Carbon::now()
        ]);
    }

    public function showProfileCompletionEmail()
    {
        $user = User::find(1);

        return view('emails.profile-completion', [
            'user' => $user
        ]);
    }

    public function sendTestEmail()
    {
        $user = User::find(1);
        var_dump($user->getEmail());

        $message = (new Welcome($user))->onQueue('emails');

        $send = Mail::to($user)
            ->queue($message);

        dd($send);
    }

    public function renderEmail()
    {
        return view(
            'emails.layouts.default.layout',
            [
                'title' => config('app.name'),
            ]
        );
    }
}
