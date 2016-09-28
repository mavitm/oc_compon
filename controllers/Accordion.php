<?php namespace Mavitm\Compon\Controllers;

use Flash;
use Redirect;
use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\compon\Models\Mtmdata;
use Backend;

class Accordion extends Controller
{

    public $componPlugin    = 'accordion';

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
        'mavitm.compon.access_accordion' 
    ];

    use \Mavitm\Compon\Traits\ControllerTrait;

    public function __construct()
    {

        $this->vars['parentlist'] = true;

        parent::__construct();

        BackendMenu::setContext('Mavitm.Compon', 'compon', 'accordion_menu');
    }


//    public function listExtendQuery($query)
//    {
//
//        if(in_array($this->action, ["sublist", "reorder"])){
//            $query->where([
//                'groups' => 'accordion',
//                'parent_id' => $this->params[0]
//            ])->orderBy("sort_order","asc");
//        }else{
//            $query->where([
//                'groups' => 'accordion',
//                'parent_id' => 0
//            ]);
//        }
//    }

//    public function reorderExtendQuery($query)
//    {
//        $query->where([
//            'groups' => 'accordion',
//            'parent_id' => $this->params[0]
//        ]);
//    }

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
                        'default' => 'accordion'
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
                    'default' => 'accordion'
                ],
            ]);
        }


    }

    public function index()
    {
//        $this->addCss('/plugins/mavitm/compon/assets/css/bootstrap.min.css');
//        $this->addCss('/plugins/mavitm/compon/assets/css/compon.css');
//        $this->addJs('/plugins/mavitm/compon/assets/js/bootstrap.min.js');
//        $this->addJs('/plugins/mavitm/compon/assets/js/compon.js');
//        $this->addJs('/modules/backend/widgets/lists/assets/js/october.list.js');
//
//        $this->pageTitle = 'Accordion grupları';
//        $this->bodyClass = 'slim-container side-panel-not-fixed';
//        $parentData = Mtmdata::where(['groups' => 'accordion','parent_id' => 0])->orderBy("title","acs")->get();
//        $this->vars['parents'] = $parentData;
//        $this->vars['toolbar'] = $this->makePartial('list_toolbar');

        $this->asExtension('ListController')->index();
    }


    public function create()
    {
        if(!empty($this->params[0])){
            if( $this->params[0] == "subcreate" )
            {
                if(!empty($this->params[1])){
                    $parent = Mtmdata::where("id",$this->params[1])->first();
                    $this->pageTitle = $parent->title.' - '.e(trans('mavitm.compon::lang.accordion.newItem'));
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
                $this->pageTitle = $parent->title.' - '.e(trans('mavitm.compon::lang.accordion.edit'));
            }
        }

        $this->asExtension('FormController')->update($this->params[0]);
    }

//    public function sublist()
//    {
//        if(empty($this->params[0])){
//            return redirect()->to(Backend::url('mavitm/compon/accordion'));
//        }
//
//        $parent = Mtmdata::where("id",$this->params[0])->first();
//        $this->pageTitle = $parent->title;
//        $this->vars['parentlist'] = false;
//        $this->vars['parent'] = $parent;
//        $this->asExtension('ListController')->index();
//    }

}