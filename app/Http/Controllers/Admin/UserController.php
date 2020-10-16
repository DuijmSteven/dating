<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\UserManager;
use App\Services\UserActivityService;

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
    public function __construct(
        UserManager $userManager,
        UserActivityService $userActivityService
    ) {
        $this->userManager = $userManager;
        parent::__construct($userActivityService);
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
                'message' => 'The user was deleted successfully'
            ];

            return redirect()->back()->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The user was not deleted due to an exception.'
            ];

            return redirect()->back()->with('alerts', $alerts);
        }
    }
}
