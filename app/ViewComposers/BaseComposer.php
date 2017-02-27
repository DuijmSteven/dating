<?php

namespace App\ViewComposers;

use App\Managers\UserManager;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class BaseComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        ViewFacade::share(
            'authenticatedUser',
            UserManager::getAndFormatAuthenticatedUser()
        );
    }
}
