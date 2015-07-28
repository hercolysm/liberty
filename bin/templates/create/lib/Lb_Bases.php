<?php

/**
 *  Controlador de base de dados
 * @author Lucas Brito <lucas080795@hotmail.com>
 */
class Lb_Bases{
    
    protected $_primary = null;
    protected $_name = null;
    protected $_db;
    public $_sql_resp = null;
    
    /**
     * Inicia configuração PDO
     * @param PDO|Array $conf configuração PDO | Configuração de tabela e chave primaria
     * @param PDO $pdo configuração de PDO caso seja enviado um array no primeiro parametro
     */
    public function __construct($conf = null,$pdo = null) {
        if(is_array($conf)){
            $this->_name = $conf["name"];
            $this->_primary = $conf["primary"];
            $conf = $pdo;
        }
        // Caso o PDO estaja como vazio pega valor padrão de conexão
        if($conf==null){
            $conf = $GLOBALS['PDO'];
        }
        
        $this->_db = $conf;
        
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
     * @param int|Array|String $id Inteiro Primário
     */
    public function update($col = array(),$id = 0){
        // Cria bind de colunas com valores
        $bind = $this->bind($col);
        
        // Caso tenha enviado um array cria um bind
        if(is_array($id)){
            $where = implode(" AND ",$this->bind($id));            
        }
        // Caso seja enviado somente um inteiro então define-se como chave primaria
        elseif(is_int($id)){
            $where = "`".$this->_primary."`='".$id."'";
        }else{
            $where = $id;
        }
        
        $sql = "UPDATE `".$this->_name."` SET ".implode(",",$bind)." WHERE $where";
     	$this->_sql_resp = $sql;	   
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
	$this->_sql_resp = $sql;
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
	$this->_sql_resp = "SELECT * FROM `".$this->_name."` ".$where." ".$order;
        return $_consulta->fetchAll();
    }
    
    /**
     * Realiza delete de um dado especifico
     * @param int $primary Chave primaria para exclusão
     */
    public function delete($primary){
        // Inicializa PDo
        $PDO = $this->_db;
        
        // Caso tenha enviado um array cria um bind
        if(is_array($primary)){
            $where = implode(" AND ",$this->bind($primary));            
        }
        // Caso seja enviado somente um inteiro então define-se como chave primaria
        elseif(is_int($primary)){
            $where = "`".$this->_primary."`='".$primary."'";
        }
        // Caso seja uma string entende que é uma string de condições
        else{
            $where = $primary;
        }
        
        // Realiza exclusão
        $_consulta = $PDO->query("DELETE FROM `".$this->_name."` WHERE $where");
        $this->_sql_resp = "DELETE FROM `".$this->_name."` WHERE `".$this->_primary."`='$primary'";
        return $_consulta;
    }
    
    /**
     * Realiza delete com um fetch
     * @param String $where Condições para exclusão
     */
    public function deleteWhere($where = null){
        // Inicializa PDo
        $PDO = $this->_db;
        // WHere
        $sql_where = empty($where) ? null : " WHERE $where";
        
        // Realiza consulta
        $_consulta = $PDO->query("DELETE FROM `".$this->_name."` $sql_where ");
     	$this->_sql_resp = "DELETE FROM `".$this->_name."` $sql_where ";   
        return $_consulta;
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
    
    /**
    * Retorna SQL de ultima execução
    * @return String
    */
    public function getSQL(){
	return $this->_sql_resp;
    }
    
    
    /**
     * Multiplas tabelas
     * @param \Lb_Base $Base Base
     * @param String $id nome do campo que compara com a chave primaria da classe child
     * @param String chave primaria comparadora da classe parent (Caso vaizio pega chave padrão)
     * @return \Lb_Bases_Multi
     */
    public function multi($Base,$id,$id_primary = null){
        $multi = new  Lb_Bases_Multi();
        $multi->primary_child = $id;
        $multi->primary_parent = empty($id_primary) ? $this->_primary : $id_primary;        
        $multi->table_child = $Base->_name;
        $multi->table_parent = $this->_name;        
        $multi->_db = $this->_db;
        return $multi;
    }


    
    
    ####################### Métodos de construção de telas ###########################
    
    
    /**
     * Cria opções do selectbox
     * @param Fetch $context 
     * @param String $text Texto Exibido
     * @param String $value Campo que deve ficar como valor
     * @param String $selected Opção selecionado
     * @example getOptions($Table->fetch(),"Nome:@nome@ Sobrenome:@sobronome@","id_nome",1); Imprime <option value=ID_DA_LINHA>Nome:NOME ATUAL Sobrenome:SOBRENOME</option>
     */
    public function getOptions($context = null,$text = null,$value = null ,$selected = null){
        
        if(is_array($context)==false){
            $context = $this->fetch();
        }
        
        // Se foi enviado fetch
        if(is_array($context)){ 
            // Caso valor seja vazio
            $value = empty($value) ? $this->_primary : $value;
            
            foreach($context as $retorno):
                $text_convert = self::getListSeparaWithSpace($text, $retorno); 
                
                // Valor selecionado
                $sel = ($retorno["$value"]==$selected) ? 'selected' : null;
                
                print '<option value="'.$retorno["$value"].'" '.$sel.' >'.$text_convert.'</option>';
                
            endforeach;
            
        }
        
        
    }
    
    /**
     * Cria linhas da tabela com ou sem o cabeçalho (caso enviado)
     * @param Fetch $context Contexto
     * @param Array $head Conteudo do cabeçalho
     * @param Array $content Conteudo da tabela
     */
    public function getRowsTable($context = null,$head = array(),$content = array()){
         if(is_array($context)==false){
            $context = $this->fetch();
        }
        
        // Verifica se foi enviado cabeçalho
        if(count($head)>0){
            print '<thead>';
                print '<tr>';
                foreach($head as $value):
                        print '<th>'.$value.'</th>';
                endforeach;
                print '</tr>';
            print '</thead>';
        }
        
        print '<tbody>';
        foreach($context as $retorno):
            print '<tr>';
                // Le cada coluna
                foreach($content as $value):
                    // Converte colunas para consultando de tabela
                    $text = self::getListSeparaWithSpace($value, $retorno); 
                    // Imprime valor
                    print '<td>'.$text.'</td>';
                endforeach;
            print '</tr>';
        endforeach;
        print '</tbody>';
    }
    
    /**
     * Retorna lista com as colunas que contem @str@
     * @param String $str
     * @param PDO::Fetch $fetch
     * @return Array
     */
    private static function getListSeparaWithSpace($str,$fetch){
        $_lista = array();
        $array_result = array();
        preg_match_all("/\@[a-zA-Z0-9\_\-]*\@/",$str,$array_result);
        // Lista de nomes
        $_lista = $array_result[0];
        
        $texto = $str;
        
        foreach($_lista as $nome):
            $value = str_replace("@",null,$nome);
            if(isset($fetch["$value"])){
                $texto = str_replace($nome,$fetch["$value"],$texto);
            }
        endforeach;
        
        return $texto;
        
    }
	
    
}

