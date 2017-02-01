<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Traits;

use Flash;
use Redirect;
use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\compon\Models\Mtmdata;
use Backend;

trait ControllerTrait {

    //public $componPlugin    = 'carousel';

    public function listExtendQuery($query)
    {
        if(in_array($this->action, ["sublist", "reorder"])){
            $query->where([
                'groups'    => $this->componPlugin,
                'parent_id' => $this->params[0],
            ])->orderBy("sort_order","asc");
        }else{
            $query->where([
                'groups'    => $this->componPlugin,
                'parent_id' => 0
            ]);
        }
    }

    public function reorderExtendQuery($query)
    {
        $query->where([
            'groups'    => $this->componPlugin,
            'parent_id' => $this->params[0]
        ]);
    }

    public function sublist()
    {
        if(empty($this->params[0])){
            return redirect()->to(Backend::url('mavitm/compon/'.$this->componPlugin));
        }

        $parent = Mtmdata::where("id",$this->params[0])->first();

        $this->pageTitle            = $parent->title;
        $this->vars['parentlist']   = false;
        $this->vars['parent']       = $parent;

        $this->asExtension('ListController')->index();
    }

    public function sublist_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $componId) {
                if ( (!$item = Mtmdata::find($componId)) )
                    continue;

                $item->delete();
            }

            Flash::success( e(trans('mavitm.compon::lang.compon.delete')) );
        }

        return $this->listRefresh("sublist");
    }

}