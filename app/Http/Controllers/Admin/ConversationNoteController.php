<?php

namespace App\Http\Controllers\Admin;

use App\ConversationNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Conversations\NoteCreateRequest;
use App\Managers\ConversationNoteManager as NoteManager;
use App\Services\UserActivityService;

class ConversationNoteController extends Controller
{
    private $noteManager;

    /**
     * ConversationNoteController constructor.
     * @param NoteManager $noteManager
     */
    public function __construct(
        NoteManager $noteManager,
        UserActivityService $userActivityService
    ) {
        $this->noteManager = $noteManager;
        parent::__construct($userActivityService);
    }

    /**
     * @param NoteCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * @param int $noteId
     * @return \Illuminate\Http\RedirectResponse
     */
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
