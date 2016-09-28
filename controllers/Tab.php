<?php namespace Mavitm\Compon\Controllers;

use Flash;
use Redirect;
use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\compon\Models\Mtmdata;
use Backend;

class Tab extends Controller
{
    public $componPlugin    = 'tab';

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    //public $listConfig = 'config_list.yaml';
    public $listConfig = [
        'list' => 'config_list.yaml',
        'subList' => 'config_sub_list.yaml'
    ];
    public $formConfig = 'config_form.yaml';

    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'mavitm.compon.access_tab'
    ];

    use \Mavitm\Compon\Traits\ControllerTrait;

    public function __construct()
    {

        $this->vars['parentlist'] = true;

        parent::__construct();

        BackendMenu::setContext('Mavitm.Compon', 'compon', 'tab_menu');
    }

    public function formExtendFields($form)
    {
        if(!empty($this->params[0])) {
            if ($this->params[0] == "subcreate" || ($this->action == "update" && !empty($this->params[1]))) {
                $this->vars['parentlist'] = false;

                $form->addFields([
                    'parent_id' => [
                        'type' => 'dropdown',
                        'label' => 'mavitm.compon::lang.compon.parent_id',
                        'default' => $this->params[1]
                    ],
                    'groups' => [
                        'label' => 'mavitm.compon::lang.compon.group',
                        'span' => 'right',
                        'type' => 'dropdown',
                        'default' => 'tab'
                    ],
                ]);

            }
        }

        if($this->action == "create"){
            $form->addFields([
                'groups' => [
                    'label' => 'mavitm.compon::lang.compon.group',
                    'span' => 'right',
                    'type' => 'dropdown',
                    'required' => 1,
                    'default' => 'tab'
                ],
            ]);
        }

    }

    public function create()
    {
        if(!empty($this->params[0])) {
            if ($this->params[0] == "subcreate") {
                if (!empty($this->params[1])) {
                    $parent = Mtmdata::where("id", $this->params[1])->first();
                    $this->pageTitle = $parent->title . ' - ' . e(trans('mavitm.compon::lang.tab.newItem'));
                }
            }
        }
        $this->asExtension('FormController')->create();
    }

    public function update()
    {

        if( !empty($this->params[1]) )
        {
            if(!empty($this->params[1])){
                $parent = Mtmdata::where("id",$this->params[1])->first();
                $this->pageTitle = $parent->title.' - '.e(trans('mavitm.compon::lang.tab.edit'));
            }
        }

        $this->asExtension('FormController')->update($this->params[0]);
    }
}