<?php

namespace spec\Betterpress\Wordpress\DashboardWidgets;

use AdamQuaile\PhpGlobal\Functions\FunctionInvoker;
use AdamQuaile\PhpGlobal\Functions\FunctionCreator;
use AdamQuaile\PhpGlobal\Functions\FunctionWrapper;
use Betterpress\Wordpress\DashboardWidgets\Dashboard;
use Betterpress\Wordpress\DashboardWidgets\Widget;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DashboardSpec extends ObjectBehavior
{

    function let(FunctionInvoker $functionInvoker, Widget $widget)
    {
        $functionCreator = new FunctionCreator();
        $functionWrapper = new FunctionWrapper(
            $functionCreator,
            $functionInvoker->getWrappedObject()
        );
        $this->beConstructedWith($functionWrapper);

        $widget->getSlug()->willReturn('my-widget');
        $widget->getTitle()->willReturn('My Widget');
        $widget->render()->willReturn('<p>My Widget</p>');

    }

    function it_accepts_widgets(FunctionInvoker $functionInvoker, Widget $widget)
    {
        $functionInvoker->invoke(
            'wp_add_dashboard_widget',
            Argument::that(function($args) {
                return
                    $args[0] == 'my-widget' &&
                    $args[1] == 'My Widget' &&
                    Argument::that(function($callable) {
                        return $callable() == '<p>My Widget</p>';
                    })->scoreArgument($args[2]);

            })
        )->shouldBeCalled();
        $this->addWidget($widget);
    }

    function it_accepts_side_widgets(FunctionInvoker $functionInvoker, Widget $widget)
    {
        $functionInvoker->invoke(
            'add_meta_box',
            Argument::that(function($args) {
                return
                    $args[0] == 'my-widget' &&
                    $args[1] == 'My Widget' &&
                    Argument::that(function($callable) {
                        return $callable() == '<p>My Widget</p>';
                    })->scoreArgument($args[2]) &&
                    $args[3] == 'dashboard'
                ;
            })
        )->shouldBeCalled();
        $this->addSideWidget($widget);
    }


}
