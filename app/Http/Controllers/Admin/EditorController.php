<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\ArticleCreateRequest;
use App\Http\Requests\Admin\Articles\ArticleUpdateRequest;
use App\Managers\ArticleManager;
use App\Managers\UserManager;
use App\Services\OnlineUsersService;
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
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var OnlineUsersService
     */
    private OnlineUsersService $onlineUsersService;

    /**
     * OperatorController constructor.
     */
    public function __construct(
        UserManager $userManager,
        OnlineUsersService $onlineUsersService
    ) {
        parent::__construct($onlineUsersService);
        $this->userManager = $userManager;
        $this->onlineUsersService = $onlineUsersService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
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

        /** @var User $editor */
        $editor = User::with(
            User::COMMON_RELATIONS
        )
            ->withCount(
                User::EDITOR_RELATION_COUNTS
            )
            ->find($editorId);

        /** @var Collection $bots */
        $queryBuilder = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::BOT_RELATIONS
            ))
        )
            ->withCount(
                USER::BOT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
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
        $onlineIds = $this->onlineUsersService->getOnlineUserIds(1);

        $editors = User::with(
            User::COMMON_RELATIONS
        )
            ->withCount(
                User::EDITOR_RELATION_COUNTS
            )
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
        $editor = User::with(
            User::COMMON_RELATIONS
        )
            ->withCount(
                User::EDITOR_RELATION_COUNTS
            )
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
                'message' => 'The editor was deleted successfully'
            ];

            return redirect()->route('admin.editors.overview')->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The editor was not deleted due to an exception.'
            ];

            return redirect()->route('admin.editors.overview')->with('alerts', $alerts);
        }
    }
}
