#!/usr/bin/php
<?php
/**
 * Controlador de Projetos da Liberty
 * @author Lucas Brito <lucas@libertynet.com.br>
 */

// Diretorio binário
$_path = __DIR__;
// Diretorio de templates
$_path_template = $_path."/templates";
// Arquivo de configurações
$conf = parse_ini_file($_path."/conf.ini");
// Diretorio atual
$cwd = getcwd();

// Diretorio de controller
$_path_controller = $cwd."/controller";
// Diretorio de views
$_path_views = $cwd."/views";
// Diretorio de bases
$_path_base = $cwd."/bases";


function erro($msg = "ERRO"){
    print $msg." [ERRO]\n\n";
    exit;
}

// Primeiro argumento
$action = isset($argv[1]) ? $argv[1] : "help";
switch ($action){
    
    // Cria ação
    case 'create':
        
        $argumento = isset($argv[2]) ? $argv[2] : null;
        // Consulta argumentos
        switch ($argumento){
            
            default :
                erro();
            break;
        
            // Cria controller
            case 'controller':
                $name = isset($argv[3]) ? $argv[3] : null;
                
                // Caso não esteja em branco
                if(!empty($name)){
                    // Aumenta letra
                    $name = ucwords($name);
                    // Verifica se já não existe controller
                    if(file_exists($_path_controller."/".$name."_Controller.php") == false){
                        // Cria diretorio em views
                        mkdir($_path_views."/$name");
                        // Cria view index
                        fopen($_path_views."/$name/index.phtml","w+");
                        // Lê o Controller de exemplo
                        $controller = null;
                        foreach(file($_path_template."/Controller.php") as $_result){
                            
                            // Substitue valores
                            $_result = str_replace(
                            array(
                                "{AUTHOR_NAME}",
                                "{AUTHOR_MAIL}",
                                "{NAME}"
                                
                            ),
                            array(
                                 $conf["name"],
                                $conf["email"],
                                $name
                            )
                            ,$_result);
                            $controller.=$_result;
                        }
                        $_c = fopen($_path_controller."/".$name."_Controller.php","w+");
                        fwrite($_c,$controller);
                        fclose($_c);
                        
                        print "Criado novo Controller $name ".$_path_controller."/".$name."_Controller.php"."[OK]\n";
                        
                    }else{
                        erro("'$name' já existe no projeto");
                    }
                }
            break;
            
            // Cria ação
            case 'action':
                $name = isset($argv[3]) ? $argv[3] : null;
                $controller = isset($argv[4]) ? ucwords($argv[4]) : null;
                // Verifica se digitou os dois
                if(!empty($name) && !empty($controller)){
                    // Verifica se controller existe
                    if(file_exists($_path_controller."/".$controller."_Controller.php")){
                        
                        // Verifica se não existe action
                        if(file_exists($_path_views."/".$controller."/".$name.".phtml") == false){
                            
                            // Cria arquivo de view
                            fopen($_path_views."/".$controller."/".$name.".phtml","w+");
                            // Caminho do controller
                            $dir = $_path_controller."/".$controller."_Controller.php";
                            // Lê controller
                            $_file_controller = file($dir);
                            // Conteudo Controller
                            $_content_controller = implode("",$_file_controller);
                            // Final do codigo
                            $_end = strrchr($_content_controller,"}");
                            // Conteudo do action (metodo)
                            $_content_action= "\n\tpublic function $name(){}\n".$_end;
                            // Atualiza conteudo do controller
                            $_content_controller = str_replace($_end, $_content_action, $_content_controller);
                            
                            // Abre aquivo
                            $_c = fopen($dir,"w+");
                            // Escreve no controller
                            fwrite($_c,$_content_controller);
                            // Fecha abertura de arquivo
                            fclose($_c);
                            
                            print "Criada nova action $name em $controller [OK]\n";
                            
                        }else{
                            erro("Action '$name' no Controller '$controller' já existe");
                        }
                        
                    }else{
                        erro("Controller '$controller' não existe no projeto");
                    }
                }else{
                    erro("Digite todos os argumentos");
                }
            
            break;
            
            
            case 'base':
                $name = isset($argv[3]) ? $argv[3] : null;
                $table = isset($argv[4]) ? $argv[4] : null;
                $primary = isset($argv[5]) ? $argv[5] : null;
                // Caso não esteja em branco
                if(!empty($name)){
                    // Aumenta letra
                    $name = ucwords($name);
                    // Verifica se já não existe base
                    if(file_exists($_path_base."/".$name."_Base.php") == false){
                        
                        // Lê o Base de exemplo
                        $base = null;
                        foreach(file($_path_template."/Base.php") as $_result){
                            
                            // Substitue valores
                            $_result = str_replace(
                            array(
                                "{AUTHOR_NAME}",
                                "{AUTHOR_MAIL}",
                                "{NAME}",
                                "{BASE_NAME}",
                                "{KEY_PRIMARY}"
                            ),
                            array(
                                 $conf["name"],
                                $conf["email"],
                                $name,
                                "$table",
                                "$primary"
                            )
                            ,$_result);
                            $base.=$_result;
                        }
                        $_c = fopen($_path_base."/".$name."_Base.php","w+");
                        fwrite($_c,$base);
                        fclose($_c);
                        
                        print "Criado nova Base $name ".$_path_base."/".$name."_Base.php"."[OK]\n";
                        
                    }else{
                        erro("'$name' já existe no projeto");
                    }
                }
            break;
            
            
            // Novo projeto
            case 'project':
                
                
                // Cria diretórios
                print "Criando diretório de bases [OK]\n";
                @mkdir("bases");
                print "Criando diretório de configurações [OK]\n";
                @mkdir("conf");
                print "Criando diretório de controllers [OK]\n";
                @mkdir("controller");
                print "Criando diretório de layout [OK]\n";
                @mkdir("layout");
                print "Criando diretório de lib [OK]\n";
                @mkdir("lib");
                print "Criando diretório  publico [OK]\n";
                @mkdir("public");
                print "Criando diretório de assets [OK]\n";
                @mkdir("public/assets");
                print "Criando diretório de views [OK]\n";
                @mkdir("views");
                print "Criando diretório de Inicial [OK]\n";
                @mkdir("views/Index/");
                print "Copiando modelo de Index Controller [OK]\n";
                @copy($_path_template."/create/Index_Controller.php", $_path_controller."/Index_Controller.php");
                print "Copiando modelo de Layout [OK]\n";
                @copy($_path_template."/create/layout.phtml",$cwd."/layout/index.phtml");
                print "Copiando index [OK]\n";
                @copy($_path_template."/create/index.php",$cwd."/public/index.php");
                print "Copiando conf [OK]\n";
                @copy($_path_template."/create/config.ini",$cwd."conf/");
                print "Copiando view da action index [OK]\n";
                @fopen($_path_views."/Index/index.phtml","w+");
                print "Projeto Criado com sucesso [OK]\n";
                
            break;
            
        }
        
    break;
    
    
    default:
    case 'help':
            print "Binário de gerenciador de Projeto Liberty\n";
            print "@author Lucas Brito <lucas@libertynet.com.br>\n";
            print "@version 1.0\n";
            
            print "Utilize um dos comandos abaixo:\n";
            
            print "~$ php lb.php create [controller] [name] \n\t=>Cria Controller com suas respectivas views\n\n";
            print "~$ php lb.php create [action] [name] [controller] \n\t=>Cria action a partir de um controle, com suas views\n\n";
            print "~$ php lb.php create [base] [name] [^table] [^primary] \n\t=> Cria controlador de base de dados\n\n";
            
            print "~$ php lb.php create project \n\t=> Cria Projeto Liberty\n\n";
    break;
    
    
    
}



print "\n";

?>
