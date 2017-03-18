<?php

namespace App\Managers;

use App\ConversationNote;

class ConversationNoteManager
{
    public function createNote(array $noteData)
    {
        DB::beginTransaction();

        try {
            $note = new ConversationNote([
                'user_id' => $noteData['user_id'],
                'conversation_id' => $noteData['conversation_id'],
                'title' => $noteData['title'],
                'category' => $noteData[''],
                'body' => $noteData['body']
            ]);

            $note->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
    }
}
