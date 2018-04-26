<?php

/* * 
 * 
 *  Lucas Brito <lucas@libertynet.com.br>
 * 
 */

/**
 * Description of Lb_Bases_Multi
 *
 * @author Lucas Brito <lucas@libertynet.com.br>
 */
class Lb_Bases_Multi extends Lb_Bases{
    
    var $table_parent;
    var $table_child;
    var $primary_parent;
    var $primary_child;
    var $_name;
    var $_primary;
    
    public function fetch($where = null, $order = null, $cols = NULL, $limit = NULL, $group = NULL){
        
        $pdo = $this->_db;
        
        // Caso tenha sido digitado algo no where
        if(!empty($where)){
            $where = " AND (".$where.")";
        }
        if(!empty($order)){
            $order = "ORDER BY ".$order;
        }
        
        $consulta = $pdo->query("SELECT * FROM `".$this->table_parent."`, `".$this->table_child."` WHERE `".$this->table_child."`.`".$this->primary_child."`=`".$this->table_parent."`.`".$this->primary_parent."` $where $order");
        return $consulta->fetchAll();
    }
    
    
    
}
