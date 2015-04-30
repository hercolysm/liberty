<?php

/**
 *  Controlador de base de dados
 * @author Lucas Brito <lucas080795@hotmail.com>
 */
class Lb_Bases{
    
    protected $_primary = null;
    protected $_name = null;
    protected $_db;
    
    /**
     * Inicia configuração PDO
     * @param PDO $db configuração PDO
     */
    public function __construct($db = null) {
        $this->_db = $db;
    }
    
    /**
     * Retorna linha pesquisada pelo id
     * @param int $id
     */
    public function find($id = 0){
       $find = $this->fetch($this->_primary."=".$id);
        return $find[0];
    }
    
    
    private function bind($array = array()){
        
       $_result = array();
       foreach($array as $c => $v){
           array_push($_result,"`$c`='$v'");
       }
       
       return $_result;
       
    }
    
    /**
     * Update
     * @param Array $col Colunas com os valores
     * @param int $id Inteiro Primário
     */
    public function update($col = array(),$id = 0){
        // Cria bind de colunas com valores
        $bind = $this->bind($col);
                
        $sql = "UPDATE `".$this->_name."` SET ".implode(",",$bind)." WHERE `".$this->_primary."`='".$id."'";
        
        $this->_db->query($sql);
        return $sql;
        
    }
    
    /**
     * Insere valores na tabela
     * @param Array $col Colunas com os valores
     * @return ultimo id inserido
     */
    public function insert($col = array()){
        $colunas = array();
        $valores = array();
        foreach($col as $col => $value){
            array_push($colunas,$col);
            array_push($valores,$value);
        }
        $sql = "INSERT INTO `".$this->_name."` (`".implode("`,`",$colunas)."`) VALUES('".implode("','",$valores)."')";
        $this->_db->query($sql);
        return $this->_db->lastInsertId();
    }
    
    /**
     * Retorna linha por um fetch
     * @param string $where Condição
     * @param string $order Ordem
     * @return Array Array Contendo todos os elementos encontrados
     */
    public function fetch($where = null,$order = null){
        // Inicializa PDO
        $PDO = $this->_db;
        // Caso tenha sido digitado algo no where
        if(!empty($where)){
            $where = " WHERE ".$where;
        }
        if(!empty($order)){
            $order = "ORDER BY ".$order;
        }
        // Realiza consulta
        $_consulta = $PDO->query("SELECT * FROM `".$this->_name."` ".$where." ".$order);
        return $_consulta->fetchAll();
    }
    
    /**
     * Realiza delete de um dado especifico
     * @param int $primary Chave primaria para exclusão
     */
    public function delete($primary){
        // Inicializa PDo
        $PDO = $this->_db;
        // Realiza exclusão
        $_consulta = $PDO->query("DELETE FROM `".$this->_name."` WHERE `".$this->_primary."`='$primary'");
    }
    
    /**
     * Realiza query
     * @param String $sql SQL
     * @return Array Array contendo as linhas encontradas
     */
    public function query($sql){
        $_consulta = $this->_db->query($sql);
        return $_consulta->fetchAll();
    }
    
}

