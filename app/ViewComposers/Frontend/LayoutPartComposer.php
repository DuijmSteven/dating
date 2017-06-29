<?php

namespace App\ViewComposers\Frontend;

use App\Managers\StorageManager;
use App\Managers\UserManager;
use App\User;
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
    protected function layoutPartHtml(string $layoutPart)
    {
        $layoutPartHtml = '';

        $modules = $this->getLayoutPartModules($layoutPart);

        $faker = new Generator();
        $faker->addProvider(new Text($faker));

        foreach ($modules as $module) {
            switch ($module) {
                case 'online-users':
                    $onlineUsers = (new UserManager(new User(), new StorageManager()))->latestOnline(20);

                    $viewData = [
                        'onlineUsers' => $onlineUsers->slice(0, 5)
                    ];
                    break;
                case 'shoutbox':
                    $latestMessages = [
                        [
                            'user' => User::find(2),
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => User::find(3),
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => User::find(4),
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => User::find(5),
                            'text' => $faker->realText(100)
                        ],
                        [
                            'user' => User::find(6),
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
                '<div class="Module Module_' . $layoutPart . '">' .
                    \View::make('frontend.modules.' . $module, $viewData)->render() .
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
    private function getLayoutPartModules($layoutPart): array
    {
        /** @var array $modules */
        $modules = \DB::table('modules')->select(['modules.name as name'])
            ->join('layout_part_module', 'layout_part_module.module_id', 'modules.id')
            ->join('layout_parts', 'layout_parts.id', 'layout_part_module.layout_part_id')
            ->where('layout_parts.name', $layoutPart)
            ->orderBy('layout_part_module.priority', 'asc')
            ->get()
            ->pluck('name')
            ->toArray();

        return $modules;
    }
}
