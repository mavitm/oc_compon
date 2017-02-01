<?php namespace Mavitm\Compon\Models;

use Model;
use Backend;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Cms\Classes\Controller as BaseController;

/**
 * Model
 */
class Menu extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;

    public static $lastPrefix      = "-mprefix";

    public $table       = 'mavitm_compon_mtmdata';
    public $implement   = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $rules = [];

    public $translatable = [
        'title',
        'html_description'
    ];

    protected $jsonable = ['strdata'];

    public function beforeSave()
    {
        $this->attributes['parent_id']  = $this->parent_id    = 0;
        $this->groups                   = "menu";

        if(!empty($this->strdata['parent_id'])){
            $parent = $this->where(["id" => $this->strdata['parent_id']])->first();
            $type = str_replace(self::$lastPrefix,'', $parent->parent_type);

            $this->parent_type          = $type.self::$lastPrefix;
            $this->groups               = "submenu";
        }
    }

    public function getSublistBtnAttribute()
    {
        //$project = $this->find($this->id);
        return '<a href="'.Backend::url('mavitm/compon/menus/sublist/'.$this->id).'" class="btn btn-primary btn-sm">
        <i class="icon icon-indent"></i> Sub list
        </a>';
    }

    public function getSubAddBtnAttribute()
    {
        //$project = $this->find($this->id);
        return '<a href="'.Backend::url('mavitm/compon/menus/create/subcreate/'.$this->id).'" class="btn btn-success btn-sm">
        <i class="icon icon-plus"></i> New sub item
        </a>';
    }

    public function getSubCountAttribute()
    {
        return intval($this->where("parent_type", $this->parent_type.self::$lastPrefix)->count());
    }

    public function getParentColorAttribute()
    {
        if($this->parent_id > 0){
            return '<span class="label label-danger">'.$this->parent_id.'</span>';
        }
        return '<span class="label label-info">'.$this->parent_id.'</span>';
    }

    public function getParentIdOptions(){
        $return = [0 => "Parent null"];
        $query = $this->select("id","title","groups")->where(["parent_id"=>0,"groups"=>"menu"]);


        $result = $query->get();

        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title;
            }
        }
        return $return;
    }

    public function parentIDReturnArray($controller)
    {
        $return = [0 => "Parent null"];
        $result = $this->select("id","title","groups")->where([ 'groups' => $controller, 'parent_id' => 0 ])->get();

        if(!empty($result)){
            $return = array();
            foreach($result as $e){
                $return[$e->id] = $e->id.' - '.$e->title;
            }
        }
        return $return;
    }

    public function getLocalPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }


    public function getLocalPageAttribute(){
        if(empty($this->strdata['local_page'])){
            return '';
        }
        return $this->strdata['local_page'];
    }
//    public function setLocalPageAttribute($value){
//        $this->strdata['local_page'] = $value;
//    }

    public function getExternalUrlAttribute(){
        if(empty($this->strdata['external_url'])){
            return '';
        }
        return $this->strdata['external_url'];
    }
//    public function setExternalUrlAttribute($value){
//        $this->strdata['external_url'] = $value;
//    }

    public function getIdAttrAttribute(){
        if(empty($this->strdata['id_attr'])){
            return '';
        }
        return $this->strdata['id_attr'];
    }
//    public function setIdAttrAttribute($value){
//        $this->strdata['id_attr'] = $value;
//    }

    public function getClassAttrAttribute(){
        if(empty($this->strdata['class_attr'])){
            return '';
        }
        return $this->strdata['class_attr'];
    }
//    public function setClassAttrAttribute($value){
//        $this->strdata['class_attr'] = $value;
//    }

    public function getLinkTargetAttribute(){
        if(empty($this->strdata['link_target'])){
            return '';
        }
        return $this->strdata['link_target'];
    }
//    public function setLinkTargetAttribute($value){
//        $this->strdata['link_target'] = $value;
//    }

    public function getUserVisibleAttribute(){
        if(empty($this->strdata['user_visible'])){
            return '';
        }
        return $this->strdata['user_visible'];
    }
//    public function setUserVisibleAttribute($value){
//        $this->strdata['user_visible'] = $value;
//    }

    public function getLinkHrefAttribute(){
        if(!empty($this->strdata['external_url'])){
            return $this->strdata['external_url'];
        }
        elseif(!empty($this->strdata['local_page'])){
            return (new BaseController)->pageUrl($this->strdata['local_page']);
        }

        return '#';
    }

}