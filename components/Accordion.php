<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Compon\Models\Mtmdata;

class Accordion extends ComponentBase
{

    public  $currentParentId    = 0,
            $componChildren     = [],
            $componUnique       = 'componAccordion',
            $panelDefaultColor  = 'panel-default',
            $panelColors        = [
                                    "panelDefault"     => "panel-default",
                                    "panelPrimary"     => "panel-primary",
                                    "panelSuccess"     => "panel-success",
                                    "panelInfo"        => "panel-info",
                                    "panelWarning"     => "panel-warning",
                                    "panelDanger"      => "panel-danger"
                                  ];

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.compon::lang.accordion.tab',
            'description' => 'mavitm.compon::lang.accordion.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'mavitm.compon::lang.accordion.tab',
                'description' => 'mavitm.compon::lang.accordion.groups',
                'type'        => 'dropdown'
            ],
            'panelColor' => [
                'title'       => 'mmavitm.compon::lang.accordion.panelColor',
                'type'        => 'dropdown',
                'options'     => $this->panelColors
            ]
        ];

    /*
        [
            "panel-default"     => "Default",
            "panel-primary"     => "Primary",
            "panel-success"     => "Success",
            "panel-info"        => "Info",
            "panel-warning"     => "Warning",
            "panel-danger"      => "Danger"
        ]
    */
    }

    public function getIdOptions()
    {
        $return = [0 => "Parent null"];
        $result = Mtmdata::select("id","title",'groups')->where([ 'groups' => 'accordion', 'parent_id' => 0 ])->get();
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
        $this->currentParentId = $this->page['currentParentId'] = $this->property('id');

        $color = $this->property('panelColor');
        $this->page['panelDefaultColor'] = $this->panelDefaultColor;

        if(!empty($this->panelColors[$color])){
            $this->panelDefaultColor = $this->page['panelDefaultColor'] = $this->panelColors[$color];
        }

        $this->componUnique = $this->page['componUnique'] = $this->componUnique.$this->currentParentId;
        $this->componChildren = $this->page['componChildren'] = $this->componChildrenLoads();
    }

    protected function componChildrenLoads()
    {
        return Mtmdata::where([ 'groups' => 'accordion', 'parent_id' => $this->currentParentId ])->get();
    }

}


?>