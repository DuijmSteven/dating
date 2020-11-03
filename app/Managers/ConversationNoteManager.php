<?php

namespace App\Managers;

use App\ConversationNote;
use Illuminate\Support\Facades\DB;

class ConversationNoteManager
{
    /**
     * @param array $noteData
     * @throws \Exception
     */
    public function createNote(array $noteData)
    {
        DB::beginTransaction();

        try {
            $note = new ConversationNote([
                'user_id' => $noteData['user_id'],
                'conversation_id' => $noteData['conversation_id'],
                'category_id' => $noteData['category_id'],
                'body' => $noteData['body']
            ]);

            $note->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
    }

    /**
     * @param $conversation
     * @return array
     */
    public function getParticipantNotes($conversation)
    {
        $userANotes = ConversationNote::with('noteCategory')
            ->where('user_id', $conversation->userA->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $userBNotes = ConversationNote::with('noteCategory')
            ->where('user_id', $conversation->userB->id)
            ->where('conversation_id', $conversation->id)
            ->orderBy('category_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return [$userANotes, $userBNotes];
    }
}
