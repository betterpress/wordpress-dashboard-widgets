<?php

namespace Betterpress\Wordpress\DashboardWidgets;

use AdamQuaile\PhpGlobal\Functions\FunctionWrapper;

class Dashboard
{
    /**
     * @var FunctionWrapper
     */
    private $functions;

    public function __construct(FunctionWrapper $functions)
    {
        $this->functions = $functions;
    }

    public function addWidget(Widget $widget)
    {
        $this->functions->invoke(
            'wp_add_dashboard_widget',
            [
                $widget->getSlug(),
                $widget->getTitle(),
                $this->functions->create(array($widget, 'render'))
            ]
        );
    }


    public function addSideWidget(Widget $widget)
    {
        $this->functions->invoke(
            'add_meta_box',
            [
                $widget->getSlug(),
                $widget->getTitle(),
                $this->functions->create(array($widget, 'render')),
                'dashboard'
            ]
        );
    }
}
