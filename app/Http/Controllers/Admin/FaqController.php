<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faqs\FaqCreateRequest;
use App\Http\Requests\Admin\Faqs\FaqUpdateRequest;
use App\Http\Requests\Admin\Faqs\TacCreateRequest;
use App\Http\Requests\Admin\Faqs\TacUpdateRequest;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

/**
 * Class FaqController
 * @package App\Http\Controllers\Admin
 */
class FaqController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(15);

        foreach ($faqs as $faq) {
            $faq->body = Markdown::convertToHtml($faq->body);
        }

        return view(
            'admin.faqs.overview',
            [
                'title' => 'Faq Overview - ' . \config('app.name'),
                'headingLarge' => 'Faq',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'faqs' => $faqs
            ]
        );
    }

    /**
     * @param int $faqId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $faqId)
    {
        try {
            Faq::destroy($faqId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The faq was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The faq was not deleted due to an exception.'
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
            'admin.faqs.create',
            [
                'title' => 'Create faq - ' . \config('app.name'),
                'headingLarge' => 'Faqs',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $faqId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $faqId)
    {
        return view(
            'admin.faqs.edit',
            [
                'title' => 'Edit faq - ' . \config('app.name'),
                'headingLarge' => 'Faqs',
                'headingSmall' => 'Edit',
                'faq' => Faq::find($faqId)
            ]
        );
    }

    /**
     * @param FaqCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(FaqCreateRequest $request)
    {
        try {
            Faq::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The faq was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The faq was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param FaqUpdateRequest $request
     * @param int $faqId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FaqUpdateRequest $request, int $faqId)
    {
        try {
            $faq = Faq::find($faqId);
            $faq->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The faq was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The faq was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
