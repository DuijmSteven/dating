<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Managers\UserManager;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController
{
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

    public function getUserById(int $userId)
    {
        try {
            $user = $this->userManager->getUserById($userId);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 404);
        }

        return JsonResponse::create($user);
    }
}
