<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Admin\Users\UserSearchRequest;
use App\Managers\UserManager;
use App\Managers\UserSearchManager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserSearchController
{
    /**
     * @var UserSearchManager
     */
    private UserSearchManager $userSearchManager;
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    public function __construct(
        UserSearchManager $userSearchManager,
        UserManager $userManager
    ) {
        $this->userSearchManager = $userSearchManager;
        $this->userManager = $userManager;
    }

    public function getPaginatedSearchResults(Request $request, $page)
    {
        try {
            $searchParameters = $request->all();

            $validator = Validator::make($searchParameters, UserSearchRequest::rules());

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), 422);
            }

            /** @var User $requestingUser */
            $requestingUser = $request->user();

            $searchParametersFormatted = $this->userSearchManager->formatUserSearchArray($searchParameters);

            $relations = UserManager::getRequiredRelationsForRole((int) $searchParametersFormatted['role_id']);
            $relationCounts = UserManager::getRequiredRelationCountsForRole((int) $searchParametersFormatted['role_id']);

            $users = $this->userSearchManager->searchUsers(
                $searchParametersFormatted,
                true,
                $page,
                null,
                $relations,
                $relationCounts
            );

            return response()->json($users);
        } catch (\Exception $exception) {
            \Log::error($exception->getTraceAsString());
            return response()->json($exception->getMessage(), 500);
        }
    }
}
