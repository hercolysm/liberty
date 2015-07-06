<?php
/**
 * Teste_base 
 * @author Lucas Brito <lucas@libertynet.com.br>
 * @access index.php?go=Teste_base
 */
class Teste_base_Controller extends Lb_Controllers{

    
    var $Contatos,$Numeros,$Descricao;
    public function init(){
	      $this->Contatos = new Contatos_Base($this->_pdo);
          $this->Numeros = new Numeros_Base($this->_pdo);
          $this->Descricao = new Descricao_Base($this->_pdo);
	}

	public function index(){
	      
        $multi = $this->Contatos->multi($this->Numeros, 'id_contato');
        
	}	
}
?>
