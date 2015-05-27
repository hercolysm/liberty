<?php

/**
 * Exemplo de código Index
 * @author Name  <email>
 */
class Index_Controller extends Lb_Controllers {

    var $Usuarios_Base;
    public function init() {
        // Inicia base de usuarios
        $this->Usuarios_Base = new Usuarios_Base($this->_pdo);
        // Titulo da pagina (layout/index.phtml)
        $this->title = "Exemplo";
    }

    public function index() {
        
        // Lista completa de usuarios para mostrar na view (view/Index/index.phtml)
        $this->lista_usuarios = $this->Usuarios_Base->fetch();
        
        $this->id_usuario = null;
        $this->nome = null;
        $this->sobrenome = null;
        $this->idade = null;
        // Caso tenha sido enviado id do usuario
        if($this->_GET("id_usuario")!=false){
            $this->id_usuario = $this->_GET("id_usuario");
            
            // Consulta linha onde a chave primaria é igual ao id do usuario
            $consulta = $this->Usuarios_Base->find($this->id_usuario);                      
            $this->nome = $consulta["nome"];
            $this->sobrenome = $consulta["sobrenome"];
            $this->idade = $consulta["idade"];
        }
       
        
    }


	/**
	* Documentacao
	**/
	public function cadastrar(){
        
         // Se foi enviado nome
        if($this->_POST("nome")!=false){
            
            // Arary de execução
            $array = array(
                "nome"=>$this->_POST("nome"),
                "sobrenome"=>$this->_POST("sobrenome"),
                "idade"=>$this->_POST("idade")
            );
            
            // Verifica se é edição (Caso o id_usuario venha diferente de vazio)
            if($this->_POST("id_usuario")!=null){
                // Realiza edição a partir da chave primaria
              $this->Usuarios_Base->update($array,$this->_POST("id_usuario"));  
            }else{ // Cadastro ( id_usuario veio como vazio)
                // Realiza cadastro
                $this->Usuarios_Base->insert($array);
            }
            $this->redirect($this->url(array("action"=>"index")));
        }
        
    }

	/**
	* Deletar Usuario
	**/
	public function deletar(){
        
        // Id do usuario vindo por metodo get
        $id_usuario = $this->_GET("id_usuario");
        
        // Realiza exclusão direto no banco pelo seu id
        $this->Usuarios_Base->delete($id_usuario);
        
        $this->redirect($this->url(array("action"=>"index")));
        
    }
}

?>
