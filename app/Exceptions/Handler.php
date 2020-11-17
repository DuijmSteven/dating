<?php

namespace App\Exceptions;

use App\Mail\Exception;
use App\Role;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        $traceAsString = $exception->getTraceAsString();
        $traceAsStringParts = str_split($traceAsString, 1900);

        $message = $exception->getMessage();

        $logArray = [];

        if (request()->user()) {
            $roleId = request()->user()->roles[0]->id;

            $logArray['Site'] = [
                'ID' => config('app.site_id'),
                'Name' => config('app.name'),
                'URL' => config('app.url'),
            ];

            $logArray['User'] = [
                'Role' =>  Role::roleDescriptionPerId()[$roleId],
                'ID' => request()->user()->getId(),
                'Username' => request()->user()->getUsername(),
                'Created at' => request()->user()->getCreatedAt(),
            ];

            if (request()->user()->isPeasant()) {
                $logArray['User']['Credits'] = request()->user()->account->credits;
            }
        }

        if (request()) {
            $logArray['Request'] = [
                'URL' =>  request()->fullUrl(),
            ];

            if (request()->user()->isPeasant()) {
                $logArray['User']['Credits'] = request()->user()->account->credits;
            }
        }

        $logArray['Exception Class'] = is_object($exception) ? get_class($exception) : null;
        $logArray['Exception Message'] = $message;
;

        if (count($traceAsStringParts) > 1) {
            $loop = 0;
            foreach ($traceAsStringParts as $part) {
                $logArray['Stack Trace Part ' . ($loop + 1)] = $part;
                $loop++;
            }
        } else {
            $logArray['Stack Trace'] = $traceAsString;
        }

        Log::channel('slackExceptions')
            ->error(
                'Site ID: ' . config('app.site_id') . ' - ' . config('app.url'),
                $logArray
            );

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect()->route('home');
        }

        if (
            $exception &&
            !$exception instanceof ValidationException
        ) {
            /** @var User $user */
            $user = request()->user();
            $exceptionEmail = (
                new Exception(
                    $user,
                    config('app.site_id'),
                    config('app.name'),
                    config('app.url'),
                    $exception->getMessage(),
                    $exception->getTraceAsString(),
                    is_object($exception) ? get_class($exception) : null,
                    $request->url()
                )
            )
            ->onQueue('emails');

            Mail::to('develyvof.exceptions@gmail.com')
                ->queue($exceptionEmail);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('/');
    }
}
