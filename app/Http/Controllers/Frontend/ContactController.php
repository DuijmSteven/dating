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
                'title' => config('app.name') . ' - Contact',
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

            // TODO set proper to
            Mail::to('orestis.palampougioukis@gmail.com')
                ->queue($contactEmail);

            toast()->message(
                'Thank you for your message! We will reply shortly.',
                'success'
            );

        } catch (\Exception $exception) {
            \Log::error($exception);

            toast()->message(
                'There was a problem while sending your message. Please try again.',
                'error'
            );
        }

        return redirect()->back()->with('errors');
    }
}
