<?php

namespace App\Http\Controllers;

use App\Mail\Welcome;
use App\User;
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
        $user = User::find(3);

        return view('emails.welcome', [
            'user' => $user
        ]);
    }

    public function showMessageReceivedEmail()
    {
        $sender = User::find(3);
        $recipient = User::find(4);

        return view('emails.message-received', [
            'sender' => $sender,
            'recipient' => $recipient,
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
}
