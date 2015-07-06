<?php
/**
* Exemplo de código Index
* @author Lucas Brito <lucas@libertynet.com.br>
*/

class Index_Controller extends Lb_Controllers{

    	public function init(){
	      //  print "Ok";
	}


	public function index(){
	       $this->title = "Novo Titulo";
           
           $file = Lb_Files::open("/var/www/liberty/conf/new.ini");
           $file->setLine("super=1");       
           var_dump($file->_content_array);
           $file->save();
           exit;
           
	}	
}
?>
