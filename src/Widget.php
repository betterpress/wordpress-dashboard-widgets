<?php

namespace Betterpress\Wordpress\DashboardWidgets;

interface Widget
{
    public function getSlug();
    public function getTitle();

    public function render();
}
