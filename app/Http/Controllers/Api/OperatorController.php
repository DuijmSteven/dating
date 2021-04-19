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
    public function getInvoiceRelatedData(Request $request, int $id, $fromDate, $untilDate)
    {
        try {
            $fromDate = (new Carbon($fromDate))
                ->tz('Europe/Amsterdam')
                ->startOfDay()
                ->setTimezone('UTC');

            $untilDate = (new Carbon($untilDate))
                ->tz('Europe/Amsterdam')
                ->endOfDay()
                ->setTimezone('UTC');

            $normalMessagesCount = ConversationMessage
                ::where('operator_id', $id)
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $untilDate)
                ->where('operator_message_type', null)
                ->count();

            $stoppedMessagesCount = ConversationMessage
                ::where('operator_id', $id)
                ->where('created_at', '>=', $fromDate)
                ->where('created_at', '<=', $untilDate)
                ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED)
                ->count();

            $operator = User::with(['meta'])->find($id);

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
