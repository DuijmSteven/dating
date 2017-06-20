<?php

namespace App\ViewComposers\Frontend;

use App\Module;
use Illuminate\View\View;

/**
 * Class RightSidebarComposer
 * @package App\ViewComposers\Frontend
 */
class RightSidebarComposer
{
    /** @var string */
    private $rightSidebarHtml;

    /**
     * RightSidebarComposer constructor.
     */
    public function __construct()
    {
        $modules = Module::with('layoutParts')
            ->whereHas('layoutParts', function ($query) {
                $query->where('name', 'right-sidebar');
            })
            ->orderBy('layout_part_module.priority', 'asc')
            ->get()
            ->toArray();

        $this->rightSidebarHtml = \View::make('frontend.components.user-activity')->render();
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