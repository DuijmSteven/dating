<?php

namespace App\Http\Controllers\Frontend;

use App\Article;
use Illuminate\Support\Str;

class ArticleController extends FrontendController
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(10);

        return view(
            'frontend.articles.overview',
            [
                'title' => $this->buildTitleWith(trans(config('app.directory_name') . '/view_titles.articles')),
                'articles' => $articles,
                'description' => trans(config('app.directory_name') . '/articles.description')
            ]
        );
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->first();

        return view(
            'frontend.articles.show',
            [
                'title' =>  $article->title . ' - ' . $this->buildTitleWith(trans(config('app.directory_name') . '/view_titles.articles')),
                'description' => $article->meta_description ?? Str::limit($article->getBody(), 155),
                'article' => $article,
                'markdownInstance' => new \Markdown()
            ]
        );
    }
}
