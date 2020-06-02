<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreatePublicChatItemRequest;
use App\Managers\ConversationManager;
use App\PublicChatItem;
use App\User;
use App\UserAccount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class PublicChatController
 * @package App\Http\Controllers
 */
class PublicChatController
{
    /**
     * @param int $userAId
     * @param int $userBId
     * @param int $messageIdHigherThan
     * @return JsonResponse
     */
    public function getPublicChatItems(
        int $forGender,
        int $forLookingForGender,
        int $offset = 0,
        int $limit = 0
    ) {
        try {
            $query = PublicChatItem
                ::with(['sender'])
                ->where(function ($query) use ($forGender, $forLookingForGender) {
                    $query->whereHas('sender.meta', function ($query) use ($forGender, $forLookingForGender) {
                        $query->gender = $forLookingForGender;
                        $query->looking_for_gender = $forGender;
                    });

                    $query->orWhereHas('sender.meta', function ($query) use ($forGender, $forLookingForGender) {
                        $query->looking_for_gender = $forLookingForGender;
                        $query->gender = $forGender;
                    });
                })
                ->whereHas('sender', function ($query) {
                    $query->where('active', true);
                })
                ->where('published_at', '<=', Carbon::now())
                ->orderBy('published_at', 'desc');

            if ($offset) {
                $query->skip($offset);
            }

            if ($limit) {
                $query->take($limit);
            }

            $chatItems = $query->get();

            return JsonResponse::create($chatItems);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

    public function getPublicChatItemsWithIdHigherThan(
        int $messageIdHigherThan,
        int $forGender,
        int $forLookingForGender
    ) {
        try {
            $query = PublicChatItem
                ::with(['sender'])
                ->where(function ($query) use ($forGender, $forLookingForGender) {
                    $query->whereHas('sender.meta', function ($query) use ($forGender, $forLookingForGender) {
                        $query->gender = $forLookingForGender;
                        $query->looking_for_gender = $forGender;
                    });

                    $query->orWhereHas('sender.meta', function ($query) use ($forGender, $forLookingForGender) {
                        $query->looking_for_gender = $forLookingForGender;
                        $query->gender = $forGender;
                    });
                })
                ->whereHas('sender', function ($query) {
                    $query->where('active', true);
                })
                ->where('published_at', '<=', Carbon::now())
                ->where('id', '>', $messageIdHigherThan)
                ->orderBy('published_at', 'desc');

            $chatItems = $query->get();

            return JsonResponse::create($chatItems);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 500);
        }
    }

//    /**
//     * Unused
//     *
//     * @param CreatePublicChatItemRequest $createPublicChatItemRequest
//     * @return JsonResponse
//     */
//    public function post(CreatePublicChatItemRequest $createPublicChatItemRequest) {
//        try {
//            DB::beginTransaction();
//
//            $messageData = $createPublicChatItemRequest->all();
//            $senderId = $messageData['sender_id'];
//            /** @var User $sender */
//            $sender = User::find($senderId);
//
//            $senderCredits = $sender->account->credits;
//
//            if ($senderCredits < 1)
//            {
//                return JsonResponse::create('Not enough credits', 403);
//            }
//
//            $publicChatItem = new PublicChatItem();
//            $publicChatItem->setBody($messageData['body']);
//            $publicChatItem->setSenderId($messageData['sender_id']);
//            $publicChatItem->setType($messageData['type']);
//            $publicChatItem->save();
//
//            /** @var UserAccount $senderAccount */
//            $senderAccount = $sender->account;
//
//            $senderAccount->setCredits($senderCredits - 1);
//            $senderAccount->save();
//
//            DB::commit();
//
//            return JsonResponse::create(true);
//        } catch (\Exception $exception) {
//            DB::rollBack();
//
//            return JsonResponse::create($exception->getMessage(), 500);
//        }
//    }
}
