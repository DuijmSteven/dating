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
                'category' => $noteData['category'],
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
