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
                'title' => 'Terms & Conditions - ' . \config('app.name'),
                'tac' => Tac::where('language', Config::get('app.locale'))->first()
            ]
        );
    }

    public function showPrivacy()
    {
        return view(
            'frontend.misc.privacy',
            [
                'title' => 'Privacy - ' . \config('app.name'),
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
                'title' => 'FAQ - ' . \config('app.name'),
            ]
        );
    }
}
