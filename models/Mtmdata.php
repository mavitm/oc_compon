<?php namespace Mavitm\Compon\Models;

use Model;
use Backend;

/**
 * Model
 */
class Mtmdata extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $table = 'mavitm_compon_mtmdata';
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $rules = [];

    public $translatable = [
        'title',
        'html_description'
    ];

    protected $jsonable = ['strdata'];

    /*public $timestamps = false; */

    public $attachOne = [
        'carouselimg' => 'System\Models\File'
    ];

    public static $groupOrtions = [
        "tab" => "Tab",
        "accordion" => "Accordion",
        "carousel" => "Carousel"
    ];

    public function beforeSave()
    {
        if(!is_numeric($this->parent_id) || empty($this->parent_id)){
            $this->parent_id = 0;
        }

        if($this->parent_id > 0){
            $parent = $this->select("id","title","group")->where(["id"=>$this->parent_id])->first();

            //$sub = $this->where("parent_id",$this->parent_id)->orderBy("sort_order","desc")->first();

            $this->group = $parent->group;
        }

        if(!array_key_exists($this->group, self::$groupOrtions)){
            $this->group = "carousel";
        }
    }

    public function getSublistBtnAttribute()
    {
        //$project = $this->find($this->id);
        return '<a href="'.Backend::url('mavitm/compon/'.$this->group.'/sublist/'.$this->id).'" class="btn btn-primary btn-sm">
        <i class="icon icon-indent"></i> Sub list
        </a>';
    }

    public function getSubCountAttribute()
    {
        return $this->where("parent_id", $this->id)->count();
    }

    public function getGroupStrAttribute()
    {
        return self::$groupOrtions[$this->group];
    }

    public function getParentColorAttribute()
    {
        if($this->parent_id > 0){
            return '<span class="label label-danger">'.$this->parent_id.'</span>';
        }
        return '<span class="label label-info">'.$this->parent_id.'</span>';
    }

    public function getCarouselimagesAttribute()
    {
        $project = $this->find($this->id);
        return '<img src="'.$project->carouselimg->getThumb(120, 50).'" />';
    }

    public function getParentIdOptions(){
        $return = [0 => "Parent null"];
        $query = $this->select("id","title","group")->where(["parent_id"=>0]);

        if(!empty($this->group)){
            $query->where("group",$this->group);
        }

        $result = $query->get();

        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title." (".$e->group.")";
            }
        }
        return $return;
    }

    public function parentIDReturnArray($controller)
    {
        $return = [0 => "Parent null"];
        $result = $this->select("id","title","group")->where([ 'group' => $controller, 'parent_id' => 0 ])->get();

        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title." (".$e->group.")";
            }
        }
        return $return;
    }

    public function accordionParentIds(){
        return $this->parentIDReturnArray('accordion');
    }

    public function tabParentIds(){
        return $this->parentIDReturnArray('tab');
    }

    public function carouselParentIds(){
        return $this->parentIDReturnArray('carousel');
    }

    public function getGroupOptions(){
        return self::$groupOrtions;
    }
}