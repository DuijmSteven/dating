<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Managers\UserManager;
use App\Milestone;
use App\MilestoneUser;
use App\Services\UserActivityService;
use App\User;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController
{
    /** @var UserManager */
    private $userManager;

    /**
     * @var UserActivityService
     */
    private UserActivityService $userActivityService;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(
        UserManager $userManager,
        UserActivityService $userActivityService
    ) {
        $this->userManager = $userManager;
        $this->userActivityService = $userActivityService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserById(int $userId)
    {
        try {
            $user = $this->userManager->getUserById($userId);
        } catch (\Exception $exception) {
            return JsonResponse::create($exception->getMessage(), 404);
        }

        return JsonResponse::create($user);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserCredits(int $userId)
    {
        return $this->userManager->getUserCredits($userId);
    }

    /**
     * @return JsonResponse
     */
    public function getOnlineUserIds()
    {
        return JsonResponse::create(
            $this->userActivityService->getOnlineUserIds(
                $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
            )
        );
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function acceptedWelcomeMessageMilestone(int $userId)
    {
        /** @var User $user */
        $user = User::find($userId);

        $acceptedWelcomeMessageMilestone = MilestoneUser::where('user_id', $userId)
            ->where('milestone_id', Milestone::ACCEPTED_WELCOME_MESSAGE)->first();

        if (!$acceptedWelcomeMessageMilestone) {
            $user->milestones()->attach(Milestone::ACCEPTED_WELCOME_MESSAGE);
        }

        return JsonResponse::create('success');
    }

}
