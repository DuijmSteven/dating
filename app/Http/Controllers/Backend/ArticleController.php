<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Article;
use Carbon\Carbon;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Backend
 */
class ArticleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view(
            'backend.articles.index',
            [
                'title' => 'Articles Overview - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Articles',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'articles' => Article::orderBy('created_at', 'desc')->paginate(5)
            ]
        );
    }

    public function destroy(int $articleId)
    {
        try {
            Article::destroy($articleId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'alert',
                'message' => 'The article was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
