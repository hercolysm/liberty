<?php
/**
*	Controlador de Páginas
*	@author Lucas Brito <lucas@libertynet.com.br>
*/

// Diretorio path
$_path = __DIR__."/../";
// Diretorio de views (pages)
$_path_view = $_path."/views";
// Diretorio de controller
$_path_controller = $_path."/controller";
// Diretorio de configurações
$_path_conf = $_path."/conf";
// Diretorio de layout
$_path_layout = $_path."/layout";
// Diretorio de lib
$_path_lib = $_path."/lib";

/**
Autoload de classes
@param String $class_name Nome da classe
*/
function __autoload($class_name) {
	global $_path_controller,$_path_lib;
	$dir = $_path_controller."/".$class_name.".php";
        // Verifica se class_name contem Lb(Liberty)
        if(strstr($class_name,"Lb_")){
            require_once $_path_lib."/".$class_name.".php";
            return ;
        }
        
	if(file_exists($dir)){
		require_once $dir;
	}else{
		print "Página não encontrada $dir";
		exit;
	}
}

// Abre aquivo de configurações
$_conf = parse_ini_file($_path_conf."/config.ini",true);
// Verifica se existe database
if(isset($_conf["database"])){
    try{
        $PDO = new PDO($_conf["database"]["adapter"].":host=".$_conf["database"]["host"].";dbname=".$_conf["database"]["dbname"].";port=".$_conf["database"]["port"],$_conf["database"]["user"],$_conf["database"]["pass"]);
    }catch(PDOException $e){
        print "Erro na conexão do banco de dados: ".$_conf["database"]["dbname"];
    }
}
// Página (GO)
$_page_go = (isset($_GET['go']) && !empty($_GET['go'])) ? ucwords($_GET['go']) : 'Index';

// Ação (action)
$_page_action = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : 'index';
// Nome do Controller
$__p = $_page_go."_Controller";
$_controller = new $__p;
// Define PDO para pagina
$_controller->_pdo = $PDO;
// Inicia valor inicial do controlador
$_controller->init();
// Inicia ação
$_controller->$_page_action();
// Inicia layout
$_controller->__get_layout();



?>
