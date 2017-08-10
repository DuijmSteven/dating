<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends FrontendController
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();

        return view(
            'frontend.articles.overview',
            [
                'title' => 'Articles Overview - ' . \config('app.name'),
                'articles' => $articles
            ]
        );
    }

    public function show($articleId)
    {
        $article = Article::where('id', $articleId)->first();

        return view(
            'frontend.articles.show',
            [
                'title' => 'Article - ' . $article->title . \config('app.name'),
                'article' => $article
            ]
        );
    }
}
