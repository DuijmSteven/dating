<?php

namespace App\Http\Controllers\Frontend;

use App\Faq;
use App\Tac;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Config;

class MiscController extends FrontendController
{
    public function showTac()
    {
        $tacContent = Tac::where('language', Config::get('app.locale'))->first()->getContent();

        $tacContent = str_replace('{{ siteName }}', ucfirst(\config('app.name')), $tacContent);

        return view(
            'frontend.misc.tac',
            [
                'tacContent' => Markdown::convertToHtml($tacContent),
                'title' => $this->buildTitleWith(trans('view_titles.tac')),
                'description' => trans('tac.description'),
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

        foreach ($faqs as $faq) {
            $faq->title = str_replace('{{ siteName }}', ucfirst(\config('app.name')), $faq->title);
            $faq->body = str_replace('{{ siteName }}', ucfirst(\config('app.name')), $faq->body);
            $faq->body = str_replace('{{ companyInfoEmail }}', \config('company.info_email'), $faq->body);
        }

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
