<?php

namespace App\Http\Controllers\Api;

use App\Managers\UserManager;
use App\Milestone;
use App\MilestoneUser;
use App\Role;
use App\Services\UserActivityService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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

    public function getUsersPaginated(int $roleId, int $page = 1)
    {
        try {
            /** @var Collection $bots */
            $queryBuilder = User::with(
                array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::BOT_RELATIONS
                ))
            )
            ->withCount(
                User::BOT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) use ($roleId) {
                $query->where('id', $roleId);
            });

            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $bots = $queryBuilder
                ->orderBy('created_at', 'desc')
                ->paginate(20);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }

        return response()->json($bots, 200);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserById(int $userId, ?int $roleId = null)
    {
        try {
            $user = $this->userManager->getUserById($userId, $roleId);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }

        return response()->json($user);
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


    public function destroy(Request $request, int $id)
    {
        /** @var User $requestingUser */
        $requestingUser = $request->user();

        /** @var User $bot */
        $bot = User::with('createdByOperator')->find($id);

        if (
            !$requestingUser->isAdmin() &&
            $bot->createdByOperator->getId() !== $requestingUser->getId()
        ) {
            return response()->json('You cannot delete profiles that you have not created', 401);
        }

        try {
            $this->userManager->deleteUser($id);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
