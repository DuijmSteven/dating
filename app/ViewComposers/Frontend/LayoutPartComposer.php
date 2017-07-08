<?php

namespace App\ViewComposers\Frontend;

use App\Managers\StorageManager;
use App\Managers\UserManager;
use App\Module;
use App\ModuleInstance;
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
     * LayoutPartComposer constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $layoutPart
     * @return string
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
                    $onlineUsers = (new UserManager(new User(), new StorageManager()))->latestOnline(20);

                    $viewData = [
                        'onlineUsers' => $onlineUsers->slice(0, 5)
                    ];
                    break;
                case 'shoutbox':
                    $users = User::whereIn('id', [2, 3, 4, 5, 6])->get();

                    $latestMessages = [
                        [
                            'user' => $users[0],
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => $users[1],
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => $users[2],
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => $users[3],
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => $users[4],
                            'text' => $faker->realText(100)
                        ],
                    ];

                    $viewData = [
                        'messages' => $latestMessages
                    ];
                    break;
                default:
                    $viewData = [];
            }

            $layoutPartHtml = $layoutPartHtml .
                '<div class="Module Module_' . $name . '">' .
                    \View::make('frontend.modules.' . $name, $viewData)->render() .
                '</div>';
        }

        return $layoutPartHtml;
    }

    /**
     * Returns an array with the layout part's module names. First module has
     * highest priority and should be displayed on top.
     *
     * @return array
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