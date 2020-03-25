<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Kim\Activity\Activity;

/**
 * Class OperatorController
 * @package App\Http\Controllers\Admin
 */
class OperatorController extends Controller
{
    /**
     * OperatorController constructor.
     */
    public function __construct(
    ) {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Collection $bots */
        $queryBuilder = User::with([
            'meta',
            'roles',
            'profileImage',
            'operatorMessages'
        ])
            ->withCount([
                'operatorMessages',
                'operatorMessagesThisMonth'
            ])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_OPERATOR)
                    ->orWhere('id', User::TYPE_ADMIN);
            });

        $operators = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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
        $onlineIds = Activity::users(1)->pluck('user_id')->toArray();

        $operators = User::with([
            'meta',
            'roles',
            'profileImage',
            'operatorMessages'
        ])
            ->withCount([
                'operatorMessages',
                'operatorMessagesThisMonth'
            ])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_OPERATOR);
            })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->paginate(20);

        return view(
            'admin.operators.overview',
            [
                'title' => 'Online operators - ' . \config('app.name'),
                'headingLarge' => 'Operators',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'operators' => $operators
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
        $operator = User::with([
            'meta',
            'roles',
            'profileImage'
        ])
            ->withCount([
                'operatorMessages',
                'operatorMessagesThisMonth',
            ])
            ->findOrFail($operatorId);

        return view(
            'admin.operators.edit',
            [
                'title' => 'Edit Peasant - '. $operator['username'] . '(ID: '. $operator['id'] .') - ' . \config('app.name'),
                'headingLarge' => 'Operator ' . $operator['username'] . '(ID: '. $operator['id'] .')',
                'headingSmall' => 'Edit',
                'carbonNow' => Carbon::now(),
                'operator' => $operator
            ]
        );
    }
}
