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
                'title' => $this->buildTitleWith('Articles Overview'),
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
                'title' => $this->buildTitleWith('Article - ' . $article->title),
                'article' => $article,
                'markdownInstance' => new \Markdown()
            ]
        );
    }
}
