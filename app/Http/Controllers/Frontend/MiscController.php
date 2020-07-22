<?php

namespace App\Http\Controllers\Frontend;

use App\Faq;
use App\Tac;
use Illuminate\Support\Facades\Config;

class MiscController extends FrontendController
{
    public function showTac()
    {
        return view(
            'frontend.misc.tac',
            [
                'tac' => Tac::where('language', Config::get('app.locale'))->first(),
                'title' => $this->buildTitleWith(trans('view_titles.tac')),
                'description' => trans('tac.description')
            ]
        );
    }

    public function showPrivacy()
    {
        return view(
            'frontend.misc.privacy',
            [
                'title' => $this->buildTitleWith(trans('view_titles.privacy')),
                'description' => trans('privacy.description')
            ]
        );
    }

    public function showFaq()
    {
        $faqs = Faq::where('status', 1)->orderBy('priority', 'asc')->orderBy('section')->get();

        $faqSections = $faqs->unique('section')->pluck('section');

        return view(
            'frontend.misc.faq',
            [
                'faqs' => $faqs,
                'faqSections' => $faqSections,
                'title' => $this->buildTitleWith(trans('view_titles.faq')),
                'description' => trans('faq.description')
            ]
        );
    }
}
