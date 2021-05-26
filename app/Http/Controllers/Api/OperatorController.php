<?php

namespace App\Http\Controllers\Api;

use App\ConversationMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class OperatorController
 * @package App\Http\Controllers
 */
class OperatorController
{
    public function getInvoiceRelatedData(Request $request, string $username, $fromDate, $untilDate)
    {
        try {
            /** @var User $operator */
            $operator = User::where('username', $username)->get();

            $fromDate = (new Carbon($fromDate))
                ->tz('Europe/Amsterdam')
                ->startOfDay()
                ->setTimezone('UTC');

            $untilDate = (new Carbon($untilDate))
                ->tz('Europe/Amsterdam')
                ->endOfDay()
                ->setTimezone('UTC');

            $normalMessagesCount = ConversationMessage
                ::where('operator_id', $operator->getId())
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $untilDate)
                ->where('operator_message_type', null)
                ->count();

            $stoppedMessagesCount = ConversationMessage
                ::where('operator_id', $operator->getId())
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $untilDate)
                ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED)
                ->count();

            return response()->json([
                'normalMessagesCount' => $normalMessagesCount,
                'stoppedMessagesCount' => $stoppedMessagesCount,
                'operator' => $operator
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
