<?php

namespace App\Http\Controllers\Api;

use App\Managers\UserManager;
use App\Milestone;
use App\MilestoneUser;
use App\Role;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

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

    public function getCurrentUserWithRelations(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $relations = User::COMMON_RELATIONS;
        $relationCounts = [];

        if ($user->isOperator()) {
            $relations = array_merge($relations, User::OPERATOR_RELATIONS);
            $relationCounts = array_merge($relationCounts, User::OPERATOR_RELATION_COUNTS);
        } elseif ($user->isEditor()) {
            $relations = array_merge($relations, User::EDITOR_RELATIONS);
            $relationCounts = array_merge($relationCounts, User::EDITOR_RELATION_COUNTS);
        }

        $user = User
            ::with($relations)
            ->withCount($relationCounts)
            ->where('id', $user->getId())
            ->first();

        return $user;
    }

    /**
     * @param int $roleId
     * @param int $page
     * @return JsonResponse
     */
    public function getUsersPaginated(Request $request, int $roleId, int $page = 1)
    {
        try {
            /** @var User $requestingUser */
            $requestingUser = $request->user();

            $relations = ['meta', 'uniqueViews'];
            $relationCounts = [];

            if ($requestingUser->isAdmin()) {
                $relations[] = 'createdByOperator';

                if ($roleId === Role::ROLE_BOT) {
                    $relationCounts = User::BOT_RELATION_COUNTS;
                }
            }

            if ($roleId === Role::ROLE_PEASANT) {
//                $relations[] = 'account';
//                $relations[] = 'completedPayments';
//                $relations[] = 'hasViewedUnique';
//                $relationCounts[] = 'hasViewed';
                $relations = array_merge(User::PEASANT_RELATIONS, User::COMMON_RELATIONS);
                $relationCounts = User::PEASANT_RELATION_COUNTS;
            }

                /** @var Collection $bots */
            $queryBuilder = User
                ::with($relations)
                ->withCount($relationCounts)
                ->whereHas('roles', function ($query) use ($roleId) {
                    $query->where('id', $roleId);
                });

            if ($requestingUser->isEditor()) {
                $queryBuilder->where('created_by_id', $requestingUser->getId());
            }

            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            $bots = $queryBuilder
                ->orderBy('created_at', 'desc')
                ->paginate(20);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }

        $jsonResponse = response()->json($bots, 200);

        return $jsonResponse;
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

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
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
