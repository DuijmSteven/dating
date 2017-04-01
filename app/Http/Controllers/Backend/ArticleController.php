<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Article;
use App\Http\Requests\Backend\Articles\ArticleCreateRequest;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

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
        $articles = Article::orderBy('created_at', 'desc')->paginate(5);

        foreach ($articles as $article) {
            $article->body = Markdown::convertToHtml($article->body);
        }

        return view(
            'backend.articles.index',
            [
                'title' => 'Articles Overview - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Articles',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'articles' => $articles
            ]
        );
    }

    /**
     * @param int $articleId
     * @return \Illuminate\Http\RedirectResponse
     */
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
                'type' => 'error',
                'message' => 'The article was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        return view(
            'backend.articles.create',
            [
                'title' => 'Create article - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Articles',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $articleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $articleId)
    {
        return view(
            'backend.articles.edit',
            [
                'title' => 'Edit article - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Articles',
                'headingSmall' => 'Edit',
                'article' => Article::find($articleId)
            ]
        );
    }

    /**
     * @param ArticleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(ArticleCreateRequest $request)
    {
        try {
            Article::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The article was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param ArticleCreateRequest $request
     * @param int $articleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ArticleCreateRequest $request, int $articleId)
    {
        try {
            $article = Article::find($articleId);
            $article->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The article was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The article was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
