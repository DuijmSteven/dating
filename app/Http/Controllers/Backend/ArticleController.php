<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Article;
use Carbon\Carbon;

class ArticleController extends Controller
{
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
}
