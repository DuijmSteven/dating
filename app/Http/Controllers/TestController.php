<?php

namespace App\Http\Controllers;

use App\Creditpack;
use App\Mail\CreditsBought;
use App\Mail\DatingsitelijstPromo;
use App\Mail\Deactivated;
use App\Mail\MessageReceived;
use App\Mail\PleaseComeBack;
use App\Mail\ProfileCompletion;
use App\Mail\ProfileViewed;
use App\Mail\Welcome;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;

class TestController extends Controller
{
    public function exampleInvoice()
    {
        $customer = new Buyer([
            'name'          => 'Operator name',
            'address' => 'asdasdasd',
        ]);

        $item = (new InvoiceItem())
            ->title('Content diensten week ' . Carbon::now()->week . ' ' . Carbon::now()->year)
            ->pricePerUnit(2);

        $invoice = Invoice::make()
            ->name('Creditnota')
            ->notes('De factuur zal binnen 7 werkdagen worden voldaan onder vermelding van het factuurnummer.')
            ->buyer($customer)
            ->date(Carbon::now())
            ->dateFormat('d-m-Y')
            ->taxRate(0)
            ->taxableAmount(0)
            ->addItem($item);

        return $invoice->stream();
    }

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

        $welcomeEmail =
            (new Welcome(
                $user
            ));

        return $welcomeEmail->render();
    }

    public function showCreditsBoughtEmail()
    {
        $user = User::find(233);

        $creditPack = Creditpack::find(2);

        $creditsBoughtEmail =
            (new CreditsBought(
                $user,
                $creditPack,
                100
            ));

        return $creditsBoughtEmail->render();
    }

    public function showDeactivatedEmail()
    {
        $user = User::find(3);

        $deactivatedEmail =
            (new Deactivated(
                $user
            ));

        return $deactivatedEmail->render();
    }

    public function showMessageReceivedEmail()
    {
        $sender = User::find(3);
        $recipient = User::find(4);
        $messageBody = 'Hey there, it feels like it has been quite a while since we last talked! Are you up for a drink?';

        $messageReceivedEmail =
            (new MessageReceived(
                $sender,
                $recipient,
                $messageBody,
                false
            ));

        return $messageReceivedEmail->render();
    }

    public function showProfileViewedEmail()
    {
        $sender = User::find(3);
        $recipient = User::find(4);

        $profileViewedEmail =
            (new ProfileViewed(
                $sender,
                $sender,
                $recipient,
                Carbon::now()
            ));

        return $profileViewedEmail->render();
    }

    public function showPleaseComeBackEmail()
    {
        $user = User::find(233);

        $pleaseComeBackEmail =
            (new PleaseComeBack(
                $user,
                Creditpack::where('name', '!=', 'test')->orderBy('id')->get()
            ));

        return $pleaseComeBackEmail->render();
    }

    public function showDatingsitelijstPromoEmail()
    {
        $user = User::find(233);

        $datingsitelijstPromoEmail =
            (new DatingsitelijstPromo(
                $user
            ));

        return $datingsitelijstPromoEmail->render();
    }

    public function showProfileCompletionEmail()
    {
        $user = User::find(3);

        $profileCompletionEmail =
            (new ProfileCompletion(
                $user
            ));

        return $profileCompletionEmail->render();
    }

    public function sendTestEmail()
    {
        $user = User::find(3);
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
                'title' => ucfirst(\config('app.name')),
            ]
        );
    }
}
