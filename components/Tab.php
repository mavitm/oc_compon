<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Compon\Models\Mtmdata;

class Tab extends ComponentBase
{

    public  $currentParentId    = 0,
            $componChildren     = [],
            $componUnique       = 'componTab';

    use \Mavitm\Compon\Traits\ComponentsTrait;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.compon::lang.tab.tab',
            'description' => 'mavitm.compon::lang.tab.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'mavitm.compon::lang.tab.tab',
                'description' => 'mavitm.compon::lang.tab.groups',
                'type'        => 'dropdown'
            ]
        ];
    }

    public function getIdOptions()
    {
        $return = [0 => "Parent null"];
        $result = Mtmdata::select("id","title","groups")->where([ 'groups' => 'tab', 'parent_id' => 0 ])->get();
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
        $this->currentParentId = $this->page['currentParentId'] = $this->property('id');
        $this->componUnique = $this->page['componUnique'] = $this->componUnique.$this->currentParentId;
        $this->componChildren = $this->page['componChildren'] = $this->componChildrenLoads();

    }

    protected function componChildrenLoads()
    {
        $childs = Mtmdata::where([ 'groups' => 'tab', 'parent_id' => $this->currentParentId ])->get();

        $childs->each(function ($child){
            $child->setHtml($child->html_description, $this->controller);
        });
        return $childs;
    }
}


?>