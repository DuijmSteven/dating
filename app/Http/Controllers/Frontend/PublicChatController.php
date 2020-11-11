<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Requests\CreatePublicChatItemRequest;
use App\PublicChatItem;
use App\User;
use App\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PublicChatController extends FrontendController
{
    public function post(CreatePublicChatItemRequest $createPublicChatItemRequest) {
        try {
            DB::beginTransaction();

            $messageData = $createPublicChatItemRequest->all();

            $senderId = $messageData['sender_id'];
            /** @var User $sender */
            $sender = User::find($senderId);

            $senderCredits = $sender->account->credits;

            if ($senderCredits < 1)
            {
                return redirect()->route('credits.show')->with('errors');
            }

            $publicChatItem = new PublicChatItem();
            $publicChatItem->setBody($messageData['text']);
            $publicChatItem->setSenderId($messageData['sender_id']);
            $publicChatItem->setType($messageData['type']);
            $publicChatItem->setPublishedAt(Carbon::now());
            $publicChatItem->save();

            /** @var UserAccount $senderAccount */
            $senderAccount = $sender->account;

            $senderAccount->setCredits($senderCredits - 1);
            $senderAccount->save();

            DB::commit();

            toastr()->success(trans(config('app.directory_name') . '/public_chat.feedback.message_sent'));
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            DB::rollBack();
            toastr()->error(trans(config('app.directory_name') . '/public_chat.feedback.message_not_sent'));
        }

        return redirect()->back()->with('errors');

    }
}
