<?php

namespace App\Listeners;

use App\Events\LogSuccessfulLogin;

class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogSuccessfulLogin  $event
     * @return void
     */
    public function handle(LogSuccessfulLogin $event)
    {
        //
    }
}
