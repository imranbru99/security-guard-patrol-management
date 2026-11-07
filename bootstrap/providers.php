<?php

use App\Core\Providers\ModuleServiceProvider;
use App\Panels\AppPanelProvider;
use App\Panels\ClientPanelProvider;
use App\Panels\ControlPanelProvider;
use App\Panels\GuardPanelProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ModuleServiceProvider::class,
    AppPanelProvider::class,
    GuardPanelProvider::class,
    ClientPanelProvider::class,
    ControlPanelProvider::class,
];
