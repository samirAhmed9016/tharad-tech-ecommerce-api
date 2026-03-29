<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\RegistedUsers;
use App\Nova\Metrics\UserPerRole;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new UserPerRole(),
            new RegistedUsers(),
        ];
    }

    public function name()
    {
        return 'dashboard';
    }
}
