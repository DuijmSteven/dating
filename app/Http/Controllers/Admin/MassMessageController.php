<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Kim\Activity\Activity;

class MassMessageController extends Controller
{
    public function __construct(
    ) {
        parent::__construct();
    }

    public function new()
    {
        /** @var Collection $bots */
        $queryBuilder = User::with(
            User::COMMON_RELATIONS
        )
            ->withCount(
                User::EDITOR_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_EDITOR)
                    ->orWhere('id', User::TYPE_ADMIN);
            });

        $editors = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.mass-messages.new',
            [
                'title' => 'New Mass Message - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Mass Messages',
                'headingSmall' => 'New Mass Message',
                'carbonNow' => Carbon::now()
            ]
        );
    }

    public function send(Request $request)
    {
        dd($request->get('body'));
    }
}
