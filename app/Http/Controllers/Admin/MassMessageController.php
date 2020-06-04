<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\SendMassMessage;
use App\Conversation;
use App\ConversationMessage;
use App\EmailType;
use App\Http\Controllers\Controller;
use App\Mail\MessageReceived;
use App\Managers\UserManager;
use App\PastMassMessage;
use App\User;
use App\UserView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Kim\Activity\Activity;

class MassMessageController extends Controller
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    public function new()
    {
        $unlimitedUsersQuery = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_PEASANT);
        })
            ->where('active', true)
            ->whereHas('meta', function ($query) {
                $query->where('gender', User::GENDER_MALE);
                $query->where('looking_for_gender', User::GENDER_FEMALE);
            });

        $unlimitedCount = $unlimitedUsersQuery->count();

        $withoutPicUsersQuery = clone $unlimitedUsersQuery;
        $withPicUsersQuery = clone $unlimitedUsersQuery;

        $withPicUsersQuery->where(function ($query) {
            $query->whereHas('meta', function ($query) {
                $query->where('dob', '!=', null);
                $query->orWhere('city', '!=',  null);
            })
                ->orWhereHas('images');
        });

        $withPicCount = $withPicUsersQuery->count();

        $withoutPicUsersQuery
            ->whereDoesntHave('images')
            ->where(function ($query) {
                $query->whereHas('meta', function ($query) {
                    $query->where('dob', '=', null);
                    $query->where('city', '=',  null);
                    $query->where('eye_color', '=',  null);
                    $query->where('hair_color', '=',  null);
                });
            });

        $withoutPicCount = $withoutPicUsersQuery->count();

        return view(
            'admin.mass-messages.new',
            [
                'title' => 'New Mass Message - ' . \MetaConstants::getSiteName(),
                'headingLarge' => 'Mass Messages',
                'headingSmall' => 'New Mass Message',
                'pastMassMessages' => PastMassMessage::orderBy('created_at', 'desc')->get(),
                'carbonNow' => Carbon::now(),
                'userCounts' => [
                    'unlimited' => $unlimitedCount,
                    'withPic' => $withPicCount,
                    'withoutPic' => $withoutPicCount,
                ]
            ]
        );
    }

    public function send(Request $request)
    {
        try {
            $alerts[] = [
                'type' => 'success',
                'message' => 'The process has been sent to the queue. Check back in a few seconds to see if the sending of the mass message is entered in the list of past messages.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'There was as problem. ' . $exception->getMessage()
            ];
        }

        Artisan::queue(
            'mass-message:send',
            [
                'body' => $request->get('body'),
                'limitation' => $request->get('limit_message')
            ],
        )
        ->onQueue('general');

        return redirect()->back()->with('alerts', $alerts);
    }
}
