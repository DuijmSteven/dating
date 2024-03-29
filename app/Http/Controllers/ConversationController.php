<?php

namespace App\Http\Controllers;

use App\ConversationMessage;
use App\EmailType;
use App\Http\Requests\Admin\Conversations\MessageCreateRequest;
use App\Mail\MessageReceived;
use App\Managers\ConversationManager;
use App\OpenConversationPartner;
use App\Services\UserActivityService;
use App\User;
use App\UserAccount;
use DB;
use Mail;

/**
 * Class ConversationController
 * @package App\Http\Controllers
 */
class ConversationController extends Controller
{
    /** @var ConversationManager $conversationManager */
    private $conversationManager;

    /** @var ConversationMessage $conversationMessage */
    private $conversationMessage;
    /**
     * @var User
     */
    private $user;

    /**
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(
        ConversationManager $conversationManager,
        ConversationMessage $conversationMessage,
        User $user,
        UserActivityService $userActivityService
    ) {
        $this->conversationManager = $conversationManager;
        $this->conversationMessage = $conversationMessage;
        $this->user = $user;
        parent::__construct($userActivityService);
    }

    public function index()
    {
        return view('frontend.chat');
    }

    /**
     * @param MessageCreateRequest $messageCreateRequest
     * @throws \Exception
     */
    public function store(MessageCreateRequest $messageCreateRequest)
    {
        $messageData = $messageCreateRequest->all();
        $senderId = $messageData['sender_id'];
        $recipientId = $messageData['recipient_id'];

        /** @var User $recipient */
        $recipient = User::with('emailTypes')->find($recipientId);

        /** @var User $sender */
        $sender = User::find($senderId);

        $senderCredits = $sender->account->credits;

        if ($senderCredits < 1)
        {
            return response()->json('Not enough credits', 403);
        }

        try {
            DB::beginTransaction();

            $conversationMessage = $this->conversationManager->createMessage($messageData);

            if ($recipient->isPeasant()) {
                $recipientPartnerIds = OpenConversationPartner::where('user_id', $recipientId)
                    ->get()
                    ->pluck('partner_id')
                    ->toArray();

                $recipientOpenConversationPartnersCount = count($recipientPartnerIds);

                if (!in_array($senderId, $recipientPartnerIds) && $recipientOpenConversationPartnersCount < 4) {
                    $recipient->addOpenConversationPartner($sender, 1);
                }
            }

            /** @var UserAccount $senderAccount */
            $senderAccount = $sender->account;

            $senderAccount->setCredits($senderCredits - 1);
            $senderAccount->save();

            $recipientEmailTypeIds = $recipient->emailTypes->pluck('id')->toArray();

            $recipientHasMessageNotificationsEnabled = in_array(
                EmailType::MESSAGE_RECEIVED,
                $recipientEmailTypeIds
            );

            if ($recipientHasMessageNotificationsEnabled) {
                $onlineUserIds = $this->userActivityService->getOnlineUserIds(
                    $this->userActivityService::PEASANT_MAILING_ONLINE_TIMEFRAME_IN_MINUTES
                );

                if (!in_array($recipient->getId(), $onlineUserIds) && $recipient->isPeasant()) {
                    if (config('app.env') === 'production') {
                        $hasAttachment = isset($messageData['attachment']) && $messageData['attachment'] ? true : false;
                        $message = isset($messageData['message']) && strlen($messageData['message']) > 0 ? $messageData['message'] : null;

                        $messageReceivedEmail = (new MessageReceived(
                            $sender,
                            $recipient,
                            $message,
                            $hasAttachment
                        ))
                            ->onQueue('emails');
                        Mail::to($recipient)->queue($messageReceivedEmail);
                    }

                    $recipient->emailTypeInstances()->attach(EmailType::MESSAGE_RECEIVED, [
                        'email' => $recipient->getEmail(),
                        'email_type_id' => EmailType::MESSAGE_RECEIVED,
                        'actor_id' => $sender->getId()
                    ]);
                }
            }

            DB::commit();

            return response()->json('The message was sent successfully');
        } catch (\Exception $exception) {
            \Log::info(__CLASS__ . ' - ' . $exception->getMessage());

            DB::rollBack();

            throw new \Exception();
        }
    }

    public function conversationMessages($conversationId)
    {
        try {
            $messages = ConversationMessage::where('conversation_id', $conversationId)->with('sender')->get();

            return $messages;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
