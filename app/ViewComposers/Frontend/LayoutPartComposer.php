<?php

namespace App\ViewComposers\Frontend;

use App\Conversation;
use App\ConversationMessage;
use App\Managers\ConversationManager;
use App\Managers\StorageManager;
use App\Managers\UserManager;
use App\Module;
use App\ModuleInstance;
use App\Services\LatestViewedModuleService;
use App\Services\OnlineUsersService;
use App\Services\UserLocationService;
use App\Services\UserActivityService;
use App\User;
use App\View;
use Faker\Generator;
use Faker\Provider\en_US\Text;

/**
 * Class LayoutPartComposer
 * @package App\ViewComposers\Frontend
 */
class LayoutPartComposer
{
    /** @var UserManager */
    private $userManager;

    /**
     * @var LatestViewedModuleService
     */
    private LatestViewedModuleService $latestViewedModuleService;

    /**
     * LayoutPartComposer constructor.
     * @param UserManager $userManager
     */
    public function __construct(
        UserManager $userManager
    ) {
        $this->userManager = $userManager;
    }

    /**
     * @param string $layoutPart
     * @return string
     * @throws \Exception
     */
    protected function layoutPartHtml(int $layoutPartId)
    {
        $layoutPartHtml = '';

        $modules = $this->getLayoutPartModules($layoutPartId);

        $faker = new Generator();
        $faker->addProvider(new Text($faker));

        foreach ($modules as $name) {
            switch ($name) {
                case 'online-users':
                    $onlineUsers = (
                        new UserManager(
                            new User(),
                            new StorageManager(),
                            new UserLocationService(),
                            new UserActivityService(),
                            new ConversationManager(
                                new Conversation(),
                                new ConversationMessage(),
                                new StorageManager(),
                                new UserActivityService()
                            )
                        )
                    )
                    ->latestOnline(10, 12);

                    $viewData = [
                        'users' => $onlineUsers
                    ];
                    break;
                case 'latest-viewed-profiles':
                    $viewData = [
                        'users' => LatestViewedModuleService::latestUsersViewed(\Auth::user()->getId(), 6)
                    ];
                    break;
                case 'latest-viewed-by-profiles':
                    $viewData = [
                        'users' => LatestViewedModuleService::latestUsersThatHaveViewed(\Auth::user()->getId(), 6)
                    ];
                    break;
                default:
                    $viewData = [];
            }

            $layoutPartHtml = $layoutPartHtml .
                '<div class="Module Module_' . $name . '">' .
                    \View::make('frontend.modules.partials.sites.' . config('app.directory_name') . '.' . $name, $viewData)->render() .
                '</div>';
        }

        return $layoutPartHtml;
    }

    /**
     * Returns an array with the layout part's module names. First module has
     * highest priority and should be displayed on top.
     *
     * @return array
     * @throws \Exception
     */
    private function getLayoutPartModules(int $layoutPartId)
    {
        $views = View::all()->pluck('route_name', 'id')->flip();

        if (in_array(request()->route()->getName(), array_keys($views->toArray()))) {
            $viewId = $views[request()->route()->getName()];
        } else {
            throw new \Exception('View name is not in the database');
        }

        return ModuleInstance::with('module')->where('view_id', $viewId)
            ->where('layout_part_id', $layoutPartId)
            ->orderBy('priority', 'asc')
            ->get()
            ->pluck('module.name')->toArray();
    }
}
