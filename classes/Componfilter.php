<?php
namespace Mavitm\Compon\Classes;

use October\Rain\Parse\Twig;
use Cms\Classes\Controller;
use Cms\Classes\CodeBase;
use Mavitm\Compon\Models\Mtmdata as ComponModel;

class Componfilter{

    public $codeBase, $controller;

    public
        $parentRow,
        $componId,
        $componGroups;

    public $replaceParametre = [
        "panelColor" => "",
        "imgWidth" => "",
        "imgHeight" => "",
        "captionDisplay",
        "xs"=>"",
        "sm" => "",
        "md" => "",
        "lg" => ""
    ];

    protected $importComponentsName = [
        "accordion" => "Accordion",
        "carousel" => "Carousel",
        "menu" => "Menu",
        "submenu" => "Menu",
        "grid" => "Grid",
        "tab" => "Tab",

    ];

    use \October\Rain\Support\Traits\Singleton;

    public static $runComponents = [];

    public function init(){
        $this->controller = Controller::getController();
    }

    public function finderSet($id){

        $row = ComponModel::find($id);

        if($row->parent_id < 1){
            /*goruntulenecek kayit*/
            $this->parentRow        = $row;
            /* parent id bu olanlari lazim bize*/
            $this->componId         = $row->id;
            /* ne oldugo accordion, carousel vb..*/
            $this->componGroups     = $row->groups;
        }else{
            return $this->finderSet($row->parent_id);
        }

        return $this;
    }


    public function componTextFinder($text, $controller = null){

        if($controller){
            $this->controller = $controller;
        }else{
            $this->controller = Controller::getController();
        }

        $sablon = '#\[compon(.*?)\](.*?)\[\/compon\]#is';
        return preg_replace_callback($sablon, array($this, "componReplace"), $text);
    }

    public function componReplace($param){

        $parametreler   = $param[1];
        $medyaid        = $param[2];

        $this->replaceParametre = [];

        $compon = $this->finderSet($medyaid);

        if(!empty($parametreler)){
            $l = explode(' ',$parametreler);

            foreach($l as $li){
                $f = explode('=', $li);
                if(!empty($f[1])) {
                    $f = array_map("trim", $f);
                    $this->replaceParametre[$f[0]] = $f[1];
                }
            }
        }

        $this->replaceParametre['currentParentId']  = $this->componId;
        $this->replaceParametre['id']               = $this->componId;

        $obj            = null;
        $objNameSpace   = 'Mavitm\Compon\Components\\'.$this->importComponentsName[$this->componGroups];
        $objAliasName   = $this->componGroups.$this->componId;

        $componObj = $this->controller->addComponent($objNameSpace, $objAliasName, $this->replaceParametre);
        return $componObj->getController()->renderPartial($objAliasName.'::default.htm');



//        $a = $this->controller->renderPartial($objAliasName.'::default.htm', $this->replaceParametre, true);
//        return $a;//urldecode(http_build_query($param,null,';'));


//        if(array_key_exists($objAliasName,self::$runComponents)){
//            $obj = self::$runComponents[$objAliasName];
//        }else{
//            if(class_exists($objNameSpace)){
//                $obj = $runComponents[$objAliasName] = new $objNameSpace;
//            }
//        }
//
//        if($obj){
//            $obj->setRawAttr((array) $this->replaceParametre);
//            //return $obj->rawTwig($this->controller);
//            return $obj->rawReturn($this->controller, $objAliasName);
//        }

        return '';
    }

}