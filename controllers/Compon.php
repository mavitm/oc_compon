<?php namespace Mavitm\Compon\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Compon extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'mavitm.compon.access_accordion', 
        'mavitm.compon.access_carousel', 
        'mavitm.compon.access_tab' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Compon', 'main-menu-item');
    }

    public function index()
    {
        $this->addCss('/plugins/mavitm/compon/assets/css/bootstrap.min.css');
        $this->asExtension('ListController')->index();
    }


    public function listExtendQuery($query)
    {
        //$query->where(['parent_id' => 0]);
    }
}