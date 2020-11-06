<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Operators\OperatorCreateRequest;
use App\Managers\UserManager;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class OperatorController
 * @package App\Http\Controllers\Admin
 */
class OperatorController extends Controller
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * OperatorController constructor.
     */
    public function __construct(
        UserManager $userManager,
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->userManager = $userManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Collection $bots */
        $queryBuilder = User::with(
            array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::OPERATOR_RELATIONS
                )
            )
        )
            ->withCount(
                User::OPERATOR_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_OPERATOR)
                    ->orWhere('id', User::TYPE_ADMIN);
            });

        $operators = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(
            'admin.operators.overview',
            [
                'title' => 'Operator Overview - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Operator',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'operators' => $operators
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = $this->userActivityService->getOnlineUserIds(
            $this->userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
        );

        $operators = User::with(
            array_unique(array_merge(
                    User::COMMON_RELATIONS,
                    User::OPERATOR_RELATIONS
                )
            )
        )
            ->withCount(
                User::OPERATOR_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_OPERATOR);
            })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->paginate(20);

        return view(
            'admin.operators.overview',
            [
                'title' => 'Online operators - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Operators',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'operators' => $operators
            ]
        );
    }

    public function showLogin()
    {
        return view(
            'operators.login',
            [
                'title' => 'Login - Operators',
                'isAnonymousDomain' => true
            ]
        );
    }

    /**
     * @param int $operatorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messages(int $operatorId)
    {
        /** @var Collection $bots */
        $operator = User::with(['meta', 'roles', 'profileImage', 'operatorMessages'])
            ->find($operatorId);

        return view(
            'admin.messages.overview',
            [
                'title' => 'Messages sent by ' . $operator->getUsername() . ' - (' . $operator->getId() . ')' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Messages set by',
                'headingSmall' => $operator->getUsername() . ' - (ID: ' . $operator->getId() . ')',
                'carbonNow' => Carbon::now(),
                'operator' => $operator
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $operatorId)
    {
        $operator = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::OPERATOR_RELATIONS
                )
            )
        )
            ->withCount(
                User::OPERATOR_RELATION_COUNTS
            )
            ->findOrFail($operatorId);

        return view(
            'admin.operators.edit',
            [
                'title' => 'Edit Peasant - '. $operator['username'] . '(ID: '. $operator['id'] .') - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Operator ' . $operator['username'] . '(ID: '. $operator['id'] .')',
                'headingSmall' => 'Edit',
                'carbonNow' => Carbon::now(),
                'operator' => $operator
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(
            'admin.operators.create',
            [
                'title' => 'Create Operator - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Operator',
                'headingSmall' => 'Create',
                'carbonNow' => Carbon::now(),
            ]
        );
    }

    /**
     * @param OperatorCreateRequest $operatorCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(OperatorCreateRequest $operatorCreateRequest)
    {
        $operatorData = $operatorCreateRequest->all();
        $operatorData['city'] = strtolower($operatorData['city']);
        $operatorData['user']['role'] = User::TYPE_OPERATOR;
        $operatorData['user']['api_token'] = Str::random(60);

        try {
            $this->userManager->createUser($operatorData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The operator was created successfully'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            $alerts[] = [
                'type' => 'error',
                'message' => 'The operator was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->userManager->deleteUser($id);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The operator was deleted successfully'
            ];

            return redirect()->route('admin.operators.overview')->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The operator was not deleted due to an exception.'
            ];

            return redirect()->route('admin.operators.overview')->with('alerts', $alerts);
        }
    }
}
