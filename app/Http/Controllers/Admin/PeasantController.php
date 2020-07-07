<?php

namespace App\Http\Controllers\Admin;

use App\EmailType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Peasants\PeasantCreateRequest;
use App\Http\Requests\Admin\Peasants\PeasantUpdateRequest;
use App\Managers\AffiliateManager;
use App\Managers\ChartsManager;
use App\Managers\PeasantManager;
use App\Managers\StatisticsManager;
use App\Managers\UserManager;
use App\User;
use App\UserFingerprint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kim\Activity\Activity;

class PeasantController extends Controller
{
    const BAR_WIDTH = 0.3;

    /** @var PeasantManager */
    private $peasantManager;

    /**
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var ChartsManager
     */
    private ChartsManager $chartsManager;
    /**
     * @var StatisticsManager
     */
    private StatisticsManager $statisticsManager;
    /**
     * @var AffiliateManager
     */
    private AffiliateManager $affiliateManager;

    /**
     * PeasantController constructor.
     * @param PeasantManager $peasantManager
     * @param UserManager $userManager
     * @param ChartsManager $chartsManager
     */
    public function __construct(
        PeasantManager $peasantManager,
        UserManager $userManager,
        ChartsManager $chartsManager,
        StatisticsManager $statisticsManager,
        AffiliateManager $affiliateManager
    )
    {
        $this->peasantManager = $peasantManager;
        parent::__construct();
        $this->userManager = $userManager;
        $this->chartsManager = $chartsManager;
        $this->statisticsManager = $statisticsManager;
        $this->affiliateManager = $affiliateManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $peasants = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Peasant Overview - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasants, $launchDate),
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createdUntilDate($date)
    {
        $peasants = $this->statisticsManager
            ->payingUsersCreatedUntilDateQuery(
                Carbon::createFromFormat('d-m-Y', $date)
            )
            ->orderBy('id', 'desc')
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Peasants created until date - ' . \config('app.name'),
                'headingLarge' => 'Peasants created until date',
                'headingSmall' => $date,
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasants, $launchDate),
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function conversions()
    {
        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');
        $endOfToday = Carbon::now('Europe/Amsterdam')->endOfDay()->setTimezone('UTC');

        $conversions = $this->statisticsManager->affiliateConversionsBetweenQueryBuilder(
            'any',
            $launchDate,
            $endOfToday
        )
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Conversions Overview - ' . \config('app.name'),
                'headingLarge' => 'Conversions',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'peasants' => $conversions,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($conversions, $launchDate),
            ]
        );
    }

    public function fingerprints()
    {
        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        $fingerprintsWithPeasants = UserFingerprint::with(['user'])
            ->whereHas('user')
            ->whereIn('fingerprint', DB::table('user_fingerprints')
                ->select(['fingerprint', DB::raw('COUNT(*) as count')])
                ->groupBy(['fingerprint'])
                ->having('count', '>', 1)
                ->orderBy('count', 'desc')
                ->get()
                ->pluck('fingerprint')->toArray())
            ->get()
            ->groupBy('fingerprint');

        $peasants = [];

        foreach ($fingerprintsWithPeasants as $fingerprint) {
            foreach ($fingerprint as $fingerprint2) {
                $peasants[] = $fingerprint2->user;
            }
        }

        return view(
            'admin.peasants.fingerprints',
            [
                'title' => 'Duplicate Fingeprints Overview - ' . \config('app.name'),
                'headingLarge' => 'Duplicate Fingeprints',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'fingerprintsWithPeasants' => $fingerprintsWithPeasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasants, $launchDate),
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fromAffiliate(string $affiliate)
    {
        $peasants = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereHas('affiliateTracking', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Peasant Overview - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasants, $launchDate),
            ]
        );
    }

    public function validateXpartnersLead(int $peasantId)
    {
        try {
            $peasant = User::findOrFail($peasantId);

            $this->affiliateManager->validateXpartnersLead($peasant);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The lead was validated successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The was a problem.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deactivations()
    {
        $deactivatedPeasants = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->where('deactivated_at', '!=', null)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Deactivations - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Deactivations',
                'carbonNow' => Carbon::now(),
                'peasants' => $deactivatedPeasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($deactivatedPeasants, $launchDate),
            ]
        );
    }

    public function withCreditpack()
    {
        $peasantsWithCreditpack = $this->statisticsManager->peasantsWithCreditpackQueryBuilder()->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Peasant Overview - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasantsWithCreditpack,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasantsWithCreditpack, $launchDate),
            ]
        );
    }

    public function showOnMap()
    {
        \Mapper::renderJavascript();
        \Mapper::map(52.125927, 5.451147, ['zoom' => 8, 'markers' => ['animation' => 'DROP']]);

        $peasants = User::with('meta')
            ->whereHas('meta', function ($query) {
                $query->where('lat', '!=', null);
                $query->where('lng', '!=', null);
            })
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->get();

        foreach ($peasants as $peasant) {
            \Mapper::marker($peasant->meta->lat, $peasant->meta->lng);
        }

        return view('admin.peasants.map',
            [
                'title' => 'Peasants on Map - ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'On Map',
                'peasants' => $peasants
            ]
        );
    }

    public function showOnline()
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $peasants = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            )
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
            })
            ->whereIn('id', $onlineIds)
            ->orderBy('id')
            ->paginate(20);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.overview',
            [
                'title' => 'Online peasants - ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'Online',
                'carbonNow' => Carbon::now(),
                'peasants' => $peasants,
                'peasantMessagesCharts' => $this->chartsManager->getMessagesCharts($peasants, $launchDate),
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(
            'admin.peasants.create',
            [
                'title' => 'Create Peasant - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Create',
                'carbonNow' => Carbon::now(),
            ]
        );
    }

    /**
     * @param PeasantCreateRequest $peasantCreateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PeasantCreateRequest $peasantCreateRequest)
    {
        $peasantCreateRequest->formatInput();
        $peasantData = $peasantCreateRequest->all();
        $peasantData['city'] = strtolower($peasantData['city']);

        try {
            $this->peasantManager->createPeasant($peasantData);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The peasant was created successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The peasant was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $peasantId, Request $request)
    {
        $peasant = User::with(
            array_unique(array_merge(
                User::COMMON_RELATIONS,
                User::PEASANT_RELATIONS
            ))
        )
            ->withCount(
                User::PEASANT_RELATION_COUNTS
            )
            ->findOrFail($peasantId);

        $launchDate = Carbon::createFromFormat('d-m-Y H:i:s', '01-02-2020 00:00:00');

        return view(
            'admin.peasants.edit',
            [
                'title' => 'Edit Peasant - ' . $peasant['username'] . '(ID: ' . $peasant['id'] . ') - ' . \config('app.name'),
                'headingLarge' => 'Peasant',
                'headingSmall' => 'Edit',
                'availableEmailTypes' => EmailType::where('editable', 1)->get(),
                'userEmailTypeIds' => $peasant->emailTypes()
                    ->where('editable', 1)
                    ->pluck('id')
                    ->toArray(),
                'carbonNow' => Carbon::now(),
                'peasant' => $peasant,
                'peasantMessagesChart' => $this->chartsManager->createPeasantMessagesChart($peasant->getId(), $launchDate),
                'peasantMessagesMonthlyChart' => $this->chartsManager->createPeasantMessagesMonthlyChart($peasant->getId()),
            ]
        );
    }

    /**
     * @param PeasantUpdateRequest $peasantUpdateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PeasantUpdateRequest $peasantUpdateRequest)
    {
        $peasantUpdateRequest->formatInput();
        $peasantData = $peasantUpdateRequest->all();

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('id'));

            $alerts[] = [
                'type' => 'success',
                'message' => 'The user was updated successfully'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The user was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    public function messagePeasantAsBot(int $peasantId, bool $onlyOnlineBots = false)
    {
        $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

        $onlineBotIds = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereIn('id', $onlineIds)
            ->get()->pluck('id')->toArray();

        $botsQueryBuilder = User::with('meta', 'roles', 'profileImage')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            });

        if ($onlyOnlineBots) {
            $botsQueryBuilder->whereIn('id', $onlineBotIds);
        }

        return view(
            'admin.peasants.message-as-bot',
            [
                'title' => 'Message Peasant as Bot- ' . \config('app.name'),
                'headingLarge' => 'Peasants',
                'headingSmall' => 'Message peasant as bot',
                'carbonNow' => Carbon::now(),
                'peasant' => User::with('meta', 'profileImage')->find($peasantId),
                'bots' => $botsQueryBuilder
                    ->get(),
                'onlineBotIds' => $onlineBotIds
            ]
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->userManager->deleteUser($id);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The peasant was deleted successfully'
            ];

            return redirect()->route('admin.peasants.retrieve')->with('alerts', $alerts);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The peasant was not deleted due to an exception.'
            ];

            return redirect()->route('admin.peasants.retrieve')->with('alerts', $alerts);
        }
    }
}
