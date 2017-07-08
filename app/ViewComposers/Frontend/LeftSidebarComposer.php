<?php

namespace App\ViewComposers\Frontend;

use App\LayoutPart;
use Illuminate\View\View;

/**
 * Class LeftSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class LeftSidebarComposer extends LayoutPartComposer
{
    /** @var string */
    private $leftSidebarHtml;

    /** @var LayoutPart */
    private $layoutPart;

    /**
     * LeftSidebarComposer constructor.
     */
    public function __construct(LayoutPart $layoutPart)
    {
        // TODO:trivial
        $this->layoutPart = $layoutPart->find(1);
        $this->leftSidebarHtml = $this->layoutPartHtml($this->layoutPart->getId());
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
