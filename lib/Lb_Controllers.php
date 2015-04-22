<?php
/**
 * Classe de controllers
 * @author Lucas Brito <lucas@libertynet.com.br>
 */

class Lb_Controllers{
    var $__data;
    var $_layout = "index";
    var $_pdo = null;
    public function __get($name){
        return $this->__data[$name];
    }
    public function __set($name,$value){
        $this->__data[$name] = $value;
    }

    /**
     * Retorna nome do controlador
     * @return String Nome do controlador
     */
    public function getController(){
        return $GLOBALS['_page_go'];
    }
    
    /**
     * Retorna nome da action
     * @return String Nome da Action
     */
    public function getAction(){
        return $GLOBALS['_page_action'];
    }
    

    
    /**
     * Retorna layout para página
     */
    public function __get_layout(){
        require $GLOBALS['_path_layout']."/".$this->_layout.".phtml";
    }
    
    /**
     * Inicia conteudo
     */
    public function content() {
        ob_start();
        require $GLOBALS['_path_view']."/".$GLOBALS['_page_go']."/".$GLOBALS['_page_action'].".phtml";
        ob_flush();
    }
    
    
   public function url($url = array("controller"=>"index","action"=>"index")){
        //return "index.php?go=".$url["controller"]."&action=".$url["action"]
        $_get = null;
        if(isset($url["controller"])==false && isset($url["go"])==false){
            $url["controller"] = "Index";
        }
        if(isset($url["action"])==false){
            $url["action"] = "index";
        }
        foreach($url as $param => $value){
            $param = str_replace("controller","go",$param);
            $_get .= $param."=".$value."&";
        }
        return "index.php?".$_get;
    } 

    /**
     * Redireciona para uma URL especifica
     * @param String $url
     */
    public function redirect($url){
        print '<script type="text/javascript">location.href="'.$url.'"</script>';
        exit;
    }

    /**
     * Protege contra SQL injection
     * @param String str
     */
    public function iSafe($str){
        $str = mysql_real_escape_string($str);
        $str = str_replace("'","",$str);
        return $str;
    }
    
    /**
     * Retorna valores $_POST
     * @param String $name Nome do campo
     * @param Boolean $protect Se deseja proteger contra SQL Injection
     */
    public function _POST($name = '',$protect = true){
        if (isset($_POST["$name"])){
            // Se deseja proteger
            $valor = ($protect) ? $this->iSafe($_POST["$name"]) : $_POST["$name"];
            return $valor;
        }else{
            return false;
        }
    }
    /**
     * Retorna valores $_GET
     * @param String $name Nome do campo
     */
    public function _GET($name = '',$protect = true){
        if (isset($_GET["$name"])){
            // Se deseja proteger
            $valor = ($protect) ? $this->iSafe($_GET["$name"]) : $_GET["$name"];
            return $valor;
        }else{
            return false;
        }
    }
   
    
}

