<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\ArticleCreateRequest;
use App\Http\Requests\Admin\Articles\ArticleUpdateRequest;
use App\Managers\ArticleManager;
use App\User;
use Carbon\Carbon;
use DB;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Kim\Activity\Activity;

/**
 * Class EditorController
 * @package App\Http\Controllers\Admin
 */
class EditorController extends Controller
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
            'profileImage'
        ])
            ->withCount([
                'createdBots',
                'createdBotsLastMonth',
                'createdBotsThisMonth',
                'createdBotsLastWeek',
                'createdBotsThisWeek',
                'createdBotsYesterday',
                'createdBotsToday',
            ])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_EDITOR)
                    ->orWhere('id', User::TYPE_ADMIN);
            });

        $editors = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.editors.overview',
            [
                'title' => 'Editors Overview - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Editor',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'editors' => $editors
            ]
        );
    }

    /**
     * @param int $editorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createdBots(int $editorId = null)
    {
        if (!$editorId) {
            $editorId = $this->authenticatedUser->getId();
        }

        /** @var Collection $bots */
        $editor = User::with(['meta', 'roles', 'profileImage', 'createdBots'])
            ->find($editorId);

        /** @var Collection $bots */
        $queryBuilder = User::with([
            'meta',
            'roles',
            'profileImage',
            'images',
            'views',
            'uniqueViews'
        ])
            ->withCount([
                'messaged',
                'messagedToday',
                'messagedYesterday',
                'messagedThisWeek',
                'messagedLastWeek',
                'messagedYesterday',
                'messagedThisMonth',
                'messagedLastMonth',
                'messages',
                'messagesToday',
                'messagesYesterday',
                'messagesThisWeek',
                'messagesLastWeek',
                'messagesYesterday',
                'messagesThisMonth',
                'messagesLastMonth'
            ])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'bot');
            });

        $queryBuilder->where('created_by_id', $editorId);

        $bots = $queryBuilder
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.bots.overview',
            [
                'title' => 'Bots created by ' . $editor->getUsername() . ' - (' . $editor->getId() . ')' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Bots created by',
                'headingSmall' => $editor->getUsername() . ' - (ID: ' . $editor->getId() . ')',
                'carbonNow' => Carbon::now(),
                'editor' => $editor,
                'bots' => $bots,
                'editBotRoute' => 'editors.bots.edit.get'
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = Activity::users(1)->pluck('user_id')->toArray();

        $editors = User::with([
            'meta',
            'roles',
            'profileImage'
        ])
            ->withCount([
                'createdBots',
                'createdBotsLastMonth',
                'createdBotsThisMonth',
                'createdBotsLastWeek',
                'createdBotsThisWeek',
                'createdBotsYesterday',
                'createdBotsToday',
            ])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_EDITOR);
            })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->paginate(20);

        return view(
            'admin.editors.overview',
            [
                'title' => 'Online editors - ' . \config('app.name'),
                'headingLarge' => 'Editors',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'editors' => $editors
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $editorId)
    {
        $editor = User::with([
            'meta',
            'roles',
            'profileImage'
        ])
            ->withCount([
                'createdBots',
                'createdBotsLastMonth',
                'createdBotsThisMonth',
                'createdBotsLastWeek',
                'createdBotsThisWeek',
                'createdBotsYesterday',
                'createdBotsToday',
            ])
            ->findOrFail($editorId);

        return view(
            'admin.editors.edit',
            [
                'title' => 'Edit Editor - '. $editor['username'] . '(ID: '. $editor['id'] .') - ' . \config('app.name'),
                'headingLarge' => 'Editor ' . $editor['username'] . '(ID: '. $editor['id'] .')',
                'headingSmall' => 'Edit',
                'carbonNow' => Carbon::now(),
                'editor' => $editor
            ]
        );
    }
}
