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
    
}

