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
        $queryBuilder = User::with(['meta', 'roles', 'profileImage', 'operatorMessages'])
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
}
