<?php namespace Mavitm\Compon;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Mavitm\Compon\Components\Accordion'        => 'componAccordion',
            'Mavitm\Compon\Components\Tab'              => 'componTab',
            'Mavitm\Compon\Components\Carousel'         => 'componCarousel'
        ];
    }

    public function registerSettings()
    {
    }
}
