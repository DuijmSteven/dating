<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider
 * @package App\Providers
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeSidebars();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function composeSidebars(): void
    {
        $this->composeRightSidebar();
        $this->composeLeftSidebar();
    }

    private function composeLeftSidebar(): void
    {
        \View::composer(
            'frontend.layouts.default.partials.left-sidebar', 'App\ViewComposers\Frontend\LeftSidebarComposer'
        );
    }

    private function composeRightSidebar(): void
    {
        \View::composer(
            'frontend.layouts.default.partials.right-sidebar', 'App\ViewComposers\Frontend\RightSidebarComposer'
        );
    }
}
