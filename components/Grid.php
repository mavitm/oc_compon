<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Compon\Models\Mtmdata;

class Grid extends ComponentBase
{

    public  $currentParentId    = 0,
            $componChildren     = [],
            $gridX              = [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12],

            $xs, $sm, $md, $lg;


    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.compon::lang.grid.tab',
            'description' => 'mavitm.compon::lang.grid.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'mavitm.compon::lang.grid.tab',
                'description' => 'mavitm.compon::lang.grid.groups',
                'type'        => 'dropdown',
            ],
            'xs' => [
                'title'       => 'mavitm.compon::lang.grid.xsSize',
                'description' => 'col-xs-?',
                'type'        => 'dropdown',
                'options'     => $this->gridX,
                'default'     => 12
            ],
            'sm' => [
                'title'       => 'mavitm.compon::lang.grid.smSize',
                'description' => 'col-sm-?',
                'type'        => 'dropdown',
                'options'     => $this->gridX,
                'default'     => 12
            ],
            'md' => [
                'title'       => 'mavitm.compon::lang.grid.mdSize',
                'description' => 'col-md-?',
                'type'        => 'dropdown',
                'options'     => $this->gridX,
                'default'     => 12
            ],
            'lg' => [
                'title'       => 'mavitm.compon::lang.grid.lgSize',
                'description' => 'col-lg-?',
                'type'        => 'dropdown',
                'options'     => $this->gridX,
                'default'     => 12
            ]
        ];
    }

    public function getIdOptions()
    {
        $return = [0 => "Parent null"];
        $result = Mtmdata::select("id","title","groups")->where([ 'groups' => 'grid', 'parent_id' => 0 ])->get();
        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title." (".$e->group.")";
            }
        }
        return $return;
    }

    public function onRun()
    {
        $this->currentParentId  = $this->page['currentParentId'] = $this->property('id');
        $this->componChildren   = $this->page['componChildren'] = $this->componChildrenLoads();

        $this->xs   = $this->page['xs'] = $this->property('xs');
        $this->sm   = $this->page['sm'] = $this->property('sm');
        $this->md   = $this->page['md'] = $this->property('md');
        $this->lg   = $this->page['lg'] = $this->property('lg');
    }

    protected function componChildrenLoads()
    {
        return Mtmdata::where([ 'groups' => 'grid', 'parent_id' => $this->currentParentId ])->get();
    }

}


?>