<?php namespace Mavitm\Compon\Controllers;

use Flash;
use Redirect;
use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\compon\Models\Menu as MenuModel;
use Backend;

class Menus extends Controller
{
    public $componPlugin    = 'menus';

    public $treeList        = true;

    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    //public $listConfig = 'config_list.yaml';
    public $listConfig      = [
        'list'              => 'config_list.yaml',
        'subList'           => 'config_sub_list.yaml'
    ];
    public $formConfig      = 'config_sub_form.yaml';

    public $reorderConfig   = 'config_reorder.yaml';

    public $requiredPermissions = [
        'mavitm.compon.access_menu'
    ];

    public function __construct()
    {

        $this->vars['parentlist'] = true;

        parent::__construct();

        BackendMenu::setContext('Mavitm.Compon', 'compon', 'menu_menu');
    }

    public function formExtendFields($form)
    {
        if(!empty($this->params[0])) {
            if ($this->params[0] == "subcreate" || ($this->action == "update" && !empty($this->params[1]))) {

                $this->vars['parentlist'] = false;

                $form->removeField('parent_type');
                $form->removeField('parent_id');
                $form->removeField('strdata[option]');

            }else{

                $form->removeField('parent_id');
                $form->removeField('local_page');
                $form->removeField('external_url');
                $form->removeField('link_target');
                $form->removeField('strdata');

            }
        }else{

            $form->removeField('parent_id');
            $form->removeField('local_page');
            $form->removeField('external_url');
            $form->removeField('link_target');
            $form->removeField('strdata');

        }
    }

    public function create()
    {
        if(!empty($this->params[0])) {
            if ($this->params[0] == "subcreate") {

                if (!empty($this->params[1])) {
                    $parent = MenuModel::where("id", $this->params[1])->first();
                    $this->pageTitle = $parent->title . ' - ' . e(trans('mavitm.compon::lang.menu.newItem'));
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
                $parent = MenuModel::where("id",$this->params[1])->first();
                $this->pageTitle = $parent->title.' - '.e(trans('mavitm.compon::lang.menu.edit'));
            }
        }else{
            $editi = MenuModel::find($this->params[0]);
            if(strpos($editi->parent_type, MenuModel::$lastPrefix) !== false){
                return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin.'/update/'.$editi->id.'/'.$editi->id));
            }
        }

        $this->asExtension('FormController')->update($this->params[0]);
    }

    public function listExtendQuery($query)
    {
        if(in_array($this->action, ["sublist", "reorder"])){
            $type = MenuModel::find($this->params[0]);

            $query
                ->where([
                    'groups'        => 'submenu',
                    'parent_type'   => $type->parent_type.MenuModel::$lastPrefix
                ]);
        }else{
            $query->where([
                'groups'    => $this->componPlugin,
                'groups'    => 'menu'
            ]);
        }
    }

    public function reorderExtendQuery($query)
    {
        $type = MenuModel::find($this->params[0]);
        $query
            ->where('groups',       '=',    'submenu')
            ->where('parent_type',  '=',    $type->parent_type.MenuModel::$lastPrefix);
    }

    public function sublist()
    {
        if(empty($this->params[0])){
            return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin));
        }

        $parent = MenuModel::where("id", $this->params[0])->first();

        if(!$parent){
            return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin));
        }

        if($parent->groups == 'submenu'){
            $typeindex = str_replace(MenuModel::$lastPrefix, '', $parent->parent_type);
            $kybele = MenuModel::where('groups', '=', 'menu')->where('parent_type', '=', $typeindex)->first();
            if(!empty($kybele->id)){
                return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin.'/sublist/'.$kybele->id));
            }else{
                return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin));
            }
        }

        $this->pageTitle            = $parent->title;
        $this->vars['parentlist']   = false;
        $this->vars['parent']       = $parent;

        $this->asExtension('ListController')->index();
    }

    public function sublist_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $componId) {
                if ( (!$item = MenuModel::find($componId)) )
                    continue;

                $item->delete();
            }

            Flash::success( e(trans('mavitm.compon::lang.compon.delete')) );
        }

        return $this->listRefresh("sublist");
    }

}