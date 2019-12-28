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
                'title' => config('app.name') . ' - Terms & Conditions'
            ]
        );
    }

    public function showPrivacy()
    {
        return view(
            'frontend.misc.privacy',
            [
                'title' => config('app.name') . ' - Privacy',
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
                'title' => config('app.name') . ' - FAQ',
            ]
        );
    }
}
