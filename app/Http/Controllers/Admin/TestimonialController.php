<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Testimonials\TestimonialCreateRequest;
use App\Http\Requests\Admin\Testimonials\TestimonialUpdateRequest;
use App\Testimonial;
use Carbon\Carbon;

/**
 * Class TestimonialController
 * @package App\Http\Controllers\Admin
 */
class TestimonialController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $testimonials = Testimonial::with('users')->orderBy('pretend_at', 'desc')->paginate(20);

        return view(
            'admin.testimonials.overview',
            [
                'title' => 'Testimonials Overview - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Testimonials',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'testimonials' => $testimonials
            ]
        );
    }

    /**
     * @param int $testimonialId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $testimonialId)
    {
        try {
            Testimonial::destroy($testimonialId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The testimonial was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The testimonial was not deleted due to an exception.'
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
            'admin.testimonials.create',
            [
                'title' => 'Create testimonial - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Testimonials',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $testimonialId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $testimonialId)
    {
        return view(
            'admin.testimonials.edit',
            [
                'title' => 'Edit testimonial - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Testimonials',
                'headingSmall' => 'Edit',
                'testimonial' => Testimonial::with('users')->find($testimonialId)
            ]
        );
    }

    /**
     * @param TestimonialCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(TestimonialCreateRequest $request)
    {
        try {
            Testimonial::create($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The testimonial was created.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The testimonial was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param TestimonialUpdateRequest $request
     * @param int $testimonialId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TestimonialUpdateRequest $request, int $testimonialId)
    {
        try {
            $testimonial = Testimonial::find($testimonialId);
            $testimonial->update($request->all());

            $alerts[] = [
                'type' => 'success',
                'message' => 'The testimonial was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The testimonial was not updated due to an exception. ' . $exception->getMessage()
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
