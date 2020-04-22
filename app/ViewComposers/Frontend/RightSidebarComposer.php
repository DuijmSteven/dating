<?php

namespace App\ViewComposers\Frontend;

use App\LayoutPart;
use App\Managers\UserManager;
use App\Services\LatestViewedModuleService;
use Illuminate\View\View;

/**
 * Class RightSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class RightSidebarComposer extends LayoutPartComposer
{
    /** @var string */
    private $rightSidebarHtml;

    /**
     * RightSidebarComposer constructor.
     */
    public function __construct(
        UserManager $userManager
    ) {
        $this->rightSidebarHtml = $this->layoutPartHtml(2);

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
        $view->with('rightSidebarHtml', $this->rightSidebarHtml);
    }
}
