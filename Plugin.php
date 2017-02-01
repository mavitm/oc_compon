<?php namespace Mavitm\Compon;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'          => 'mavitm.compon::lang.plugin.name',
            'description'   => 'mavitm.compon::lang.plugin.description',
            'author'        => 'Mavitm',
            'icon'          => 'icon-pencil',
            'homepage'      => 'https://github.com/MaviTm/compon'
        ];
    }

    public function registerComponents()
    {
        return [
            'Mavitm\Compon\Components\Accordion'    => 'componAccordion',
            'Mavitm\Compon\Components\Tab'          => 'componTab',
            'Mavitm\Compon\Components\Carousel'     => 'componCarousel',
            'Mavitm\Compon\Components\Grid'         => 'componGrid',
            'Mavitm\Compon\Components\Menu'         => 'componMenu'
        ];
    }

    public function registerPermissions()
    {
        return [
            'mavitm.compon.access_accordion' => [
                'tab'   => 'mavitm.compon::lang.accordion.tab',
                'label' => 'mavitm.compon::lang.accordion.access_post'
            ],
            'mavitm.compon.access_carousel' => [
                'tab'   => 'mavitm.compon::lang.carousel.tab',
                'label' => 'mavitm.compon::lang.carousel.access_post'
            ],
            'mavitm.compon.access_tab' => [
                'tab'   => 'mavitm.compon::lang.tab.tab',
                'label' => 'mavitm.compon::lang.tab.access_post'
            ],
            'mavitm.compon.access_grid' => [
                'tab'   => 'mavitm.compon::lang.grid.tab',
                'label' => 'mavitm.compon::lang.grid.access_post'
            ],
            'mavitm.compon.access_menu' => [
                'tab'   => 'mavitm.compon::lang.menu.tab',
                'label' => 'mavitm.compon::lang.menu.access_post'
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'compon' => [
                'label'         => 'mavitm.compon::lang.plugin.name',
                'url'           =>  Backend::url('mavitm/compon/compon'),
                'icon'          => 'icon-list-alt',
                'permissions'   => ['mavitm.compon.*'],
                'order'         => 50,

                'sideMenu' => [
                    'accordion_menu' => [
                        'label'         => 'mavitm.compon::lang.accordion.tab',
                        'url'           => Backend::url('mavitm/compon/accordion'),
                        'icon'          => 'icon-align-justify',
                        'permissions'   => ['mavitm.compon.access_accordion']
                    ],
                    'carousel_menu' => [
                        'label'         => 'mavitm.compon::lang.carousel.tab',
                        'url'           => Backend::url('mavitm/compon/carousel'),
                        'icon'          => 'icon-arrows-h',
                        'permissions'   =>['mavitm.compon.access_carousel']
                    ],
                    'tab_menu' => [
                        'label'         => 'mavitm.compon::lang.tab.tab',
                        'url'           => Backend::url('mavitm/compon/tab'),
                        'icon'          => 'icon-folder',
                        'permissions'   => ['mavitm.compon.access_tab']
                    ],
                    'grid_menu' => [
                        'label'         => 'mavitm.compon::lang.grid.tab',
                        'url'           => Backend::url('mavitm/compon/grid'),
                        'icon'          => 'icon-columns',
                        'permissions'   => ['mavitm.compon.access_grid']
                    ],
                    'menu_menu' => [
                        'label'         => 'mavitm.compon::lang.menu.tab',
                        'url'           => Backend::url('mavitm/compon/menus'),
                        'icon'          => 'icon-bars',
                        'permissions'   => ['mavitm.compon.access_menu']
                    ]
                ]
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Mavitm\Compon\Formwidgets\Jsonable' => [
                'label' => 'jsonable',
                'code'  => 'jsonable'
            ]
        ];
    }

    public function registerSettings()
    {
    }
}
