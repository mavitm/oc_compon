<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
namespace Mavitm\Compon\Traits;
use App;
trait ComponentsTrait{

    public function thisProperties(){
        $thisProperties = new \ReflectionClass($this);
        $attrList       = $thisProperties->getProperties(\ReflectionProperty::IS_PUBLIC);

        $a = [];
        if(!empty($attrList)){

            foreach($attrList as $obj){
                if(strpos($obj->class, "Mavitm") !== false){
                    $a[$obj->name] = $obj;
                }
            }

        }
        return $a;
    }


    public function setRawAttr(Array $param){

//        $attrList           = null;
//
//        if($param) {
//            $attrList = $this->thisProperties();
//        }
//
//        if(!empty($attrList) && is_array($attrList)){
//
//            foreach ($attrList as $k=>$mi){
//                if( array_key_exists($k, $param) ){
//                    $this->$k = $param[$k];
//                    $this->page[$k] = $param[$k];
//                    $this->setProperty($k, $param[$k]);
//                    $a[$k] = $param[$k];
//
//                }elseif(!empty($param[$k])){
//                    var_dump($k);
//                    $this->setProperty($k, $param[$k]);
//                    $a[$k] = $param[$k];
//
//                }
//            }
//        }

        if(!empty($param)){
            foreach($param as $k=>$v){
                $this->setProperty($k, $param[$k]);
                $a[$k] = $param[$k];
            }
        }

        //dd($param);

        return $this;
    }

    public function rawReturn($controller, $objAliasName){
        $this->onRun();
        //return Twig::parse($this->rawTwig(), $this->getProperties());
//
//        $twig = App::make('twig.environment');
//        $template = $twig->createTemplate($this->rawTwig());
//        return $template->render($this->getProperties());

        return $controller->renderPartial($objAliasName."::default.htm", $this->getProperties());
    }

    public function rawTwig(){
        $twig       = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.strtolower(class_basename($this)).DIRECTORY_SEPARATOR;
        $twigFile   = $twig.'default.htm';

        if(is_file($twigFile)){
            return file_get_contents($twigFile);
        }
        return '';
    }

}