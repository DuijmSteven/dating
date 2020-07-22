<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\ContactRequest;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends FrontendController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showContact()
    {
        return view(
            'frontend.contact',
            [
                'title' => $this->buildTitleWith(trans('view_titles.contact')),
                'description' => trans('contact.description')
            ]
        );
    }

    /**
     * @param ContactRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postContact(ContactRequest $request)
    {
        try {
            $contactMailInstance = new Contact($request->all());

            $contactEmail = $contactMailInstance->onQueue('emails');

            Mail::to(config('company.info_email'))
                ->queue($contactEmail);

            toastr()->success(trans('contact.feedback.message_sent'));
        } catch (\Exception $exception) {
            \Log::error($exception);

            toastr()->error(trans('contact.feedback.message_not_sent'));
        }

        return redirect()->back()->with('errors');
    }
}
