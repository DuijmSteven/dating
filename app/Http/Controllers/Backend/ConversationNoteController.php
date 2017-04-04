<?php

namespace App\Http\Controllers\Backend;

use App\ConversationNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Conversations\NoteCreateRequest;
use App\Managers\ConversationNoteManager as NoteManager;

class ConversationNoteController extends Controller
{
    private $noteManager;

    public function __construct(NoteManager $noteManager)
    {
        $this->noteManager = $noteManager;
        parent::__construct();
    }

    public function postNote(NoteCreateRequest $request)
    {
        $noteData = $request->all();

        try {
            $this->noteManager->createNote($noteData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The note was created successfully'
            ];
        } catch (\Exception $exception) {

            $alerts[] = [
                'type' => 'error',
                'message' => 'The note was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    public function destroyNote(int $noteId)
    {
        try {
            $note = ConversationNote::findOrFail($noteId);
            $note->delete();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The note (ID: ' . $noteId . ') was deleted successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The note was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
