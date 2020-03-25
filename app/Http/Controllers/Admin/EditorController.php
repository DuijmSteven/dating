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
use Illuminate\Support\Collection;

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
        $queryBuilder = User::with(['meta', 'roles', 'profileImage', 'createdBots'])
            ->withCount([
                'createdBots'
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
                'title' => 'Editor Overview - ' . \MetaConstants::getSiteName(),
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
    public function createdBots(int $editorId)
    {
        /** @var Collection $bots */
        $editor = User::with(['meta', 'roles', 'profileImage', 'createdBots'])
            ->find($editorId);

        /** @var Collection $bots */
        $queryBuilder = User::with(['meta', 'roles', 'profileImage', 'images', 'views', 'uniqueViews'])
            ->withCount([
                'messaged',
                'messagedToday',
                'messagedYesterday',
                'messagedThisWeek',
                'messagedLastWeek',
                'messagedYesterday',
                'messagedThisMonth',
                'messagedLastMonth'
            ])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'bot');
            });

        $queryBuilder->where('created_by_id', $this->authenticatedUser->getId());

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
                'bots' => $bots
            ]
        );
    }
}
