<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\PublicChatItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class PublicChatController
 * @package App\Http\Controllers
 */
class PublicChatController extends Controller
{
    /**
     * @param int $forGender
     * @param int $forLookingForGender
     * @param int $offset
     * @param int $limit
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
                        $query->where('gender', $forLookingForGender);
                        $query->where('looking_for_gender', $forGender);
                    })
                    ->orWhereHas('sender', function ($query) {
                        $query->where('id', auth('api')->user()->getId());
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

    /**
     * @param int $messageIdHigherThan
     * @param int $forGender
     * @param int $forLookingForGender
     * @return JsonResponse
     */
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
                        $query->where('gender', $forLookingForGender);
                        $query->where('looking_for_gender', $forGender);
                    })
                    ->orWhereHas('sender', function ($query) {
                        $query->where('id', auth('api')->user()->getId());
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
