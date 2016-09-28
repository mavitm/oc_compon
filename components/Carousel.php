<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Compon\Models\Mtmdata;

class Carousel extends ComponentBase
{

    public  $currentParentId    = 0,
            $componChildren     = [],
            $componUnique       = 'componCarousel',
            $componWidth        = 1920,
            $componHeight       = 640;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.compon::lang.carousel.tab',
            'description' => 'mavitm.compon::lang.carousel.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'mavitm.compon::lang.carousel.tab',
                'description' => 'mavitm.compon::lang.carousel.groups',
                'type'        => 'dropdown'
            ],
            'imgWidth' => [
                'title'       => 'mavitm.compon::lang.carousel.imgWidth',
                'description' => 'mavitm.compon::lang.carousel.px',
                'type'        => 'text',
                'required'    => 1,
                'default'     => 1920
            ],
            'imgHeight' => [
                'title'       => 'mavitm.compon::lang.carousel.imgHeight',
                'description' => 'mavitm.compon::lang.carousel.px',
                'type'        => 'text',
                'required'    => 1,
                'default'     => 640
            ]
        ];
    }

    public function getIdOptions()
    {
        $return = [0 => "Parent null"];
        $result = Mtmdata::select("id","title","groups")->where([ 'groups' => 'carousel', 'parent_id' => 0 ])->get();
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
        $this->currentParentId  = $this->page['currentParentId']    = $this->property('id');
        $this->componUnique     = $this->page['componUnique']       = $this->componUnique.$this->currentParentId;
        $this->componChildren   = $this->page['componChildren']     = $this->componChildrenLoads();

        if(intval($this->property('imgWidth'))){
            $this->componWidth      = $this->page['componWidth']        = intval($this->property('imgWidth'));
        }

        if(intval($this->property('imgHeight'))){
            $this->componHeight     = $this->page['componHeight']       = intval($this->property('imgHeight'));
        }
    }

    public function imgThumb($obj)
    {
        return $obj->carouselimg->thumb($this->componWidth, $this->componHeight);
    }

    protected function componChildrenLoads()
    {
        return Mtmdata::where([ 'groups' => 'carousel', 'parent_id' => $this->currentParentId ])->get();
    }

}


?>