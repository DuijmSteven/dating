<?php

namespace App\ViewComposers\Frontend;

use App\LayoutPart;
use App\Managers\UserManager;
use App\Services\LatestViewedModuleService;
use Illuminate\View\View;

/**
 * Class LeftSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class LeftSidebarComposer extends LayoutPartComposer
{
    /** @var string */
    private $leftSidebarHtml;

    /**
     * LeftSidebarComposer constructor.
     */
    public function __construct(
        UserManager $userManager
    ) {
        $this->leftSidebarHtml = $this->layoutPartHtml(1);
        parent::__construct($userManager);
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'leftSidebarHtml' => $this->leftSidebarHtml
        ]);
    }
}
