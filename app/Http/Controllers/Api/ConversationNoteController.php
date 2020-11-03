<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\ConversationNote;
use App\Http\Validators\BotUpdate;
use App\Http\Validators\ConversationNoteCreate;
use App\Managers\ConversationManager;
use App\Managers\ConversationNoteManager;
use App\NoteCategory;
use App\OpenConversationPartner;
use App\User;
use Carbon\Carbon;
use Config;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConversationNoteController
{
    /**
     * @var ConversationNoteManager
     */
    private ConversationNoteManager $conversationNoteManager;

    /**
     * ConversationController constructor.
     * @param ConversationManager $conversationManager
     */
    public function __construct(
        ConversationNoteManager $conversationNoteManager
    ) {
        $this->conversationNoteManager = $conversationNoteManager;
    }

    public function getCategories()
    {
        try {
            $noteCategories = NoteCategory::all();

            return response()->json($noteCategories);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ConversationNoteCreate::rules());

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        try {
            $this->conversationNoteManager->createNote($request->all());

            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function destroy(Request $request, int $noteId)
    {
        /** @var User $requestingUser */
        $requestingUser = $request->user();

        /** @var ConversationNote $note */
        $note = ConversationNote::find($noteId);

        if (!($note instanceof ConversationNote)) {
            return response()->json('The note does not exist', 404);
        }

        if (
            !$requestingUser->isAdmin() &&
            $note->getUserId() !== $requestingUser->getId()
        ) {
            return response()->json('You cannot delete notes that you have not created', 401);
        }

        try {
            $note->delete();
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
