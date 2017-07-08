<?php

namespace App\Http\Controllers\Frontend;

use App\Managers\FlirtManager;


/**
 * Class FlirtController
 * @package App\Http\Controllers\Frontend
 */
class FlirtController extends FrontendController
{
    private $flirtManager;

    /**
     * FlirtController constructor.
     * @param FlirtManager $flirtManager
     */
    public function __construct(FlirtManager $flirtManager)
    {
        $this->flirtManager = $flirtManager;
        parent::__construct();
    }

    /**
     * @param int $senderId
     * @param int $recipientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(int $senderId, int $recipientId)
    {
        try {
            $this->flirtManager->send($senderId, $recipientId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The flirt was send successfully ;)'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'Error in sending flirt'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
