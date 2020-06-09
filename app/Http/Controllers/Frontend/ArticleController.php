<?php

namespace App\Http\Controllers\Frontend;

use App\Article;

class ArticleController extends FrontendController
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(10);

        return view(
            'frontend.articles.overview',
            [
                'title' => $this->buildTitleWith(trans('view_titles.articles')),
                'articles' => $articles
            ]
        );
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->first();

        return view(
            'frontend.articles.show',
            [
                'title' => $this->buildTitleWith(trans('view_titles.articles') . ' - ' . $article->title),
                'article' => $article,
                'markdownInstance' => new \Markdown()
            ]
        );
    }
}
