<?php

namespace App\Http\Controllers\Api;

use App\Managers\UserManager;
use App\Milestone;
use App\MilestoneUser;
use App\Role;
use App\Services\OnlineUsersService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController
{
    /** @var UserManager */
    private $userManager;
    /**
     * @var OnlineUsersService
     */
    private OnlineUsersService $onlineUsersService;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(
        UserManager $userManager,
        OnlineUsersService $onlineUsersService
    ) {
        $this->userManager = $userManager;
        $this->onlineUsersService = $onlineUsersService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

    public function getUsers(Request $request, int $roleId, int $page = 1)
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
            return JsonResponse::create($exception->getMessage(), 404);
        }

        return JsonResponse::create($bots);
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserById(int $userId, int $roleId = Role::ROLE_BOT)
    {
        try {
            $user = $this->userManager->getUserById($userId, $roleId);
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
        $onlineIds = $this->onlineUsersService->getOnlineUserIds();

        return JsonResponse::create($onlineIds);
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
