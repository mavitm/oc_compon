<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/

namespace Mavitm\Compon\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Compon\Models\Menu as MenuModel;

class Menu extends ComponentBase{

    /**
     * kybele id
     * @var
     */
    public $currentParentId;

    /**
     * menu group
     * @var
     */
    public $kybele;

    /**
     * menu kybele items
     * @var
     */
    public $childs;

    /**
     * active item selected
     * @var
     */
    public $activeSlug;


    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.compon::lang.menu.tab',
            'description' => 'mavitm.compon::lang.menu.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'mavitm.compon::lang.menu.tab',
                'description' => 'mavitm.compon::lang.menu.groups',
                'type'        => 'dropdown'
            ],
            'slug' => [
                'title'       => 'mavitm.compon::lang.menu.slug',
                'description' => 'mavitm.compon::lang.menu.slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
        ];
    }

    public function getIdOptions()
    {
        $return = [0 => "Parent null"];
        $result = MenuModel::select("id","title","groups")->where([ 'groups' => 'menu', 'parent_id' => 0 ])->get();
        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title." (".$e->groups.")";
            }
        }
        return $return;
    }

    public function onRun()
    {
        $this->currentParentId  = $this->page['currentParentId']    = $this->property('id');
        $this->kybele           = $this->page['kybele']             = $this->kybeleData($this->currentParentId);

        $this->activeSlug       = $this->page['activeSlug']         = $this->property('slug');

        if(empty($this->kybele->parent_type)){
            return null;
        }

        $subType                = $this->kybele->parent_type.MenuModel::$lastPrefix;
        $childs                 = MenuModel::where('parent_type', '=', $subType);

        $this->childs           = $this->page['childs']             = $childs->getNested();

    }

    protected function kybeleData($id){
        $k = MenuModel::find($id);

        if(empty($k->id)){
            return new \stdClass();
        }

        $kybele                       = new \stdClass();

        $kybele->parent_type          = $k->parent_type;
        $kybele->id_attr              = "mki".rand(0,9999);
        $kybele->class_attr           = "mki".rand(0,9999);
        $kybele->parent_tag           = "ul";
        $kybele->parent_tag_enable    = 1;
        $kybele->user_visible         = "all";

        if(is_array($k->strdata['option'])){
            foreach($k->strdata['option'] as $i=>$v){
                if(empty($v)){
                    continue;
                }
                $kybele->$i = $v;
            }
        }
        return $kybele;
    }

}