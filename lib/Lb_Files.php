<?php
/**
 * Controlador de arquivos
 * @author Lucas Brito <lucas@libertynet.com.br>
 */
class Lb_Files{
    
    private $_data = array();
    public $_content= null;
    public $_content_array = null;
    public $_path = null;
    
    
    function __set($name, $value){
        $this->_data["$name"] = $value;
    }
    function __get($name){
        if(isset($this->_data["$name"])){
            return $this->_data["$name"];
        }else{
            return null;
        }
    }
    
    
    ## Arquivos INI ##
    
    /**
     * Lê arquivo do tipo ini
     * @param String $_path Caminho do arquivo
     * @param boolean $_section Se utiliza seção (padrão false)
     * @return \Lb_Files
     */
    public static function read_ini($_path,$_section = false){
           
        // Verifica se arquivo existe
        if(file_exists($_path)){
            $ini = parse_ini_file($_path,$_section);
            // Verifica se é um ini dividido em seções
            if($_section){
                $UP = array();
                // Lê todo o array de seções para criar valores
                foreach($ini as $key => $section){
                    // Cria uma classe para seção especifica
                    $UP["$key"] = new Lb_Files();
                    // Lê a seção atual para criar valores
                    foreach($section as $param => $value){
                        // Cria um parametro para seção especifica
                        $UP["$key"]->$param = $value;
                    }
                }
                
            }else{
                $UP = new Lb_Files();
                // Lê o ini para criar valores
                foreach($ini as $param => $value){
                    $UP->$param = $value;
                }
            }
            return $UP;
        }
        return null;
        
    }
    
    /**
     * Criar/Edita arquivo ini
     * @param String $_path Caminho do arquivo
     * @param Array $_content Conteudo em array para ser passado
     * @param boolean $_section Se é dividido em seções (padrão false)
     * @example write_ini("/var/www/liberty/conf/teste.ini",array("sobre"=>array("nome"=>"Lucas","Sobrenome"=>"Brito"),"habilidades"=>array("correr"=>100)),true)
     * @example write_ini("/var/www/liberty/conf/teste.ini",array("nome"=>"Lucas","Sobrenome"=>"Brito"),false);
     */
    public static function write_ini($_path,$_content = array(),$_section = false){
        // Verifica se já existe arquivo
        if(file_exists($_path)){
            $ini = parse_ini_file($_path,$_section);
        }else{
            // Cria array vazio
            $ini = array();
        }
        
        // Lê todo conteudo passado para a função
        foreach($_content as $key => $value){
            $ini[$key] = $value;
        }
        /*
         * Agora o array $ini contem o conteudo atualizado
         */
        
        // Abre ponteiro para o arquivo
        $_fopen = fopen($_path,"w+");
        // O que vai ser escrito
        $_write = null;
        foreach ($ini as $section => $value){
            // Se é por seção
            if($_section){
                $_write.="[$section]\n";
                // Lê todos os dados da seção
                foreach($value  as $param => $c){
                    $_write.="$param=\"$c\"\n";
                }
            }
            // Não utiliza seções
            else{
                $_write.="$section=\"$value\"\n";
            }
        }
        
        // Escreve no ponteiro aberto
        fwrite($_fopen,$_write);
        // Fecha ponteiro
        fclose($_fopen);
        
    }
    
    
    ## Leitura de Arquivos ##
    
    /**
     * Abre arquivo para leitura/escrita e pegar informações
     * @param String $_path Caminho do arquivo
     * @return \Lb_Files
     */
    public static function open($_path){
        // Cria objeto da tabela
            $File = new Lb_Files();
            // Define diretorio
            $File->_path = $_path;
        if(file_exists($_path)){
            
            // Lê arquivo 
            $content_array = file($_path);
            // Salva conteudo no array
            $File->_content_array = $content_array;
            
            // Lê cada linha do arquivo salva
            $content = null;
            foreach($content_array as $line){
                // Salva conteudo da linha
                $content.=$line;
            }
            $File->content = $content;
           
        }
        // Retorna arquivo
        return $File;
        
    }
    
    /**
     * Retorna linha do arquivo
     * @param Int $line linha solicitada
     * @return String
     */
    public function getLine($line = 0){
        if(isset($this->_content_array[$line])){
            return $this->_content_array[$line];
        }else{
            return null;
        }
    }
    /**
     * Retorna o número de linhas existentes
     * @return int
     */
    public function countLine(){
        return count($this->_content_array);
    }
    
    /**
     * Insere/Substitue linha especifica (caso não seja enviado segundo parametro cria uma nova linha) (Deve-se usar $this->save())
     * @param String $str
     * @param int $line
     */
    public function setLine($str = null,$line = ''){
            $this->_content_array[$line] = $str; 
    }
    
    /**
     * Salva modificações no arquivo
     */
    public function save(){
        $fopen = fopen($this->_path,"w+");
        // Verifica se é um array o conteudo (Se tiver conteudo)
        if(is_array($this->_content_array)){
            // Lê linha por linha
            foreach($this->_content_array as $content){
                fwrite($fopen,trim($content)."\n");
            }
        }
        // Fecha ponteiro
        fclose($fopen);
    }
    
}

