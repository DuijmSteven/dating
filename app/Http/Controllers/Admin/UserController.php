<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Controllers\Controller;
use App\Managers\UserManager;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /** @var UserManager $userManager */
    protected $userManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    /**
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(int $userId)
    {
        try {
            $this->userManager->deleteUser($userId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The operator was deleted successfully'
            ];

            return redirect()->back()->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The operator was not deleted due to an exception.'
            ];

            return redirect()->back()->with('alerts', $alerts);
        }
    }
}
