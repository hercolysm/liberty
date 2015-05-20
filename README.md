# liberty
Modelo MVC da liberty - Criado e desenvolvido por Lucas Brito

Liberty MVC é a utilização do modelo MVC (Model-View-Controller) em projetos de código PHP. Utilizando de código Orientando a Objetos, o <br>
programador tem a performace de código limp e fácil de realizar qualquer tipo de modificação


# Estrutura
A estrutura do liberty é a seguinte:
<ul>
  <li>conf/ => Utilizado para configurações, arquivos .ini</li>
  <li>conf/conf.ini => Utilizado para configurações iniciais. Inicialmente de banco de dados</li>
  <li>controller/ => Diretório onde contém as classe que controlam o código do programador</li>
  <li>bases/ => Utilizado para guardar classes que controlam utilização de banco de dados nas suas especificas tabelas</li>
  <li>public/ => Diretório onde encontra-se a pasta do usuário, ou seja, o que seja publico ao mesmo. Neste diretório, pode colocar as pastas<br>
  assets, ou seja: "scripts";"folhas de estilo (css)";"imagens";"fonts".<i>(É aconselhavel que na configuração do servidor apache, a pasta inicial,<br>
  seja a "public", para que o usuário somente tenha acesso aos dados que estão na "public")</i></li>
  <li>lib/ => Pasta de biblioteca da liberty (não modificar)</li>
  <li>views/ => Pasta de views que serão o conteudo de cada controller para ser mostrado aos usuários</li>
  <li>layout/ => Pasta onde contém os layouts da página</li>
  <li>bin/ => Pasta de código executavel</li>
</ul>


# Configuração por parte do desenvolvedor
É aconselhavel que você coloque o diretório liberty/bin no "PATH" do seu sistema operacional
<br>
<strong>Linux:</strong><br>
<pre>
  vim ~/.bashrc # Abrir o bashrc do usuário<br>
  PATH=$PATH:/diretorio/liberty/bin/ # Adiciona linha em .bashrc e depois salvar o arquivo e sair<br>
  bash # Executar para atualizar no bash atual<br>
</pre>

# Criando projeto
  Para criar projeto liberty, utilize o seguinte código:
  <br>
  <pre>
    mkdir pasta # pasta onde deve ficar o sistema
    cd pasta# entra na pasta
    lb.php create project # cria projeto no diretorio atual
  </pre>
  <br>
  O script lb.php encontra-se no diretorio "bin/" ele é utilizado para facilitar a criação das funções do liberty
  
# Configurando Projeto
  Para configurar o projeto, após ser criado, entre no arquivo "conf/conf.ini", onde o mesmo é utilizado inicialmente para ocnfiguração do PDO. Com os seguintes parametros configure (Retirando o comentário inicial ";"):
  <br>
  
  ```ini
  [database]
  adapter=mysql ;Adaptador do banco de dados (Drivers PDO(mysql,pgsql,dlib))
  host=localhost ;Endereço do banco de dados
  user=root ;Usuário do banco de dados
  pass=admin ;Senha do usuário do banco de dados
  dbname=db ;Nome do banco de dados
  port=3306 ;Porta de conexão do banco de dados
  ```
  
# Como funciona a requisão pelo usuário
  Quando o usuário solicita o endereço, por exemplo:
  <br>
  <code>http://localhost/public/?go=home&action=listar</code>
  <br>
  acontece o seguinte:
  <ol>
    <li> O Servidor acessa "public/index.php" com os seguintes parametros:
      <ol type="a">
        <li> go = home </li>
        <li> action = mostrar </li>
      </ol>
    </li>
    <li> O Index trata os parametros, traduzindo-os:
      <ol type="a">
        <li> go = controller </li>
        <li> action = ação </li>
      </ol>
    </li>
    <li> O public/index.php chama o controller (home)</li>
    <li> Imprime na tela o layout padrão (Caso não seja solicitado para não imprimir)</li>
    <li>Executa as ações do método init() do controller</li>
    <li> Acessa a action (mostrar) </li>
    <li> Executa as ações da "action" (mostrar) </li>
    <li> Acessa e imprime a view da action do controller (views/Home/mostrar.phtml)</li>
  </ol>
    
      
# Controllers
Para criar controllers(que no caso são as páginas carregadas pelo método GET "go") utiliza-se o seguinte código abaixo:
<br>
<code>lb.php create controller "meuControlador"</code>
<br>
Quando criado o controller, o código poderá ser acessado:
<br>
<code>controller/MeuControlador_Controller.php</code>
<br>
Onde o mesmo apresentará o seguinte código:
<br>

```php
<?php
/**
 * MeuControlador
 * @author Nome <email@servidor.com.br>
 */
class MeuControlador_Controller extends Lb_Controllers{

    	public function init(){
	    }

	    public function index(){
      }	
}
?>
```
Ao criar controller é automaticamente criado uma action chamada index (Views/MeuControlador/index.phtml), quando o usuário acessar
<code>"http://localhost/public/?go=meuControlador" </code> (sem enviar o valor "action") é carregada a ação "index"
<br>

<strong>Utilizando Controllers+Views(o que vai ser mostrado ao usuário):</strong>
<br>
controller/MeuControlador_Controller.php:
```php
<?php
/**
 * MeuControlador
 * @author Nome <email@servidor.com.br>
 */
class MeuControlador_Controller extends Lb_Controllers{

    	public function init(){
    	  $this->texto = "Meu nome é:";
	    }

	    public function index(){
	      $this->nome = "Lucas Brito";
      }	
}
?>
```
Views/MeuControlador/index.phtml
```php
<?php
  print "Ola?<br>";
  print $this->texto."<br>";
  print $this->nome;
?>
```

Resultado:
```php
Ola?
Meu nome é....
Lucas Brito
```
Quando iniciamos solicitando a url <code>http://localhost/public/?go=meuControlador</code> o liberty carrega primeiro o método init() logo após o método index() e imprimindo com o layout. Quando criamos uma variavel utilizando <code>$this->variavel = "0";</code> ela automaticamente fica acessável para todo a base atual (Actions do Controller / Views / Layout)

# Criando Action(Ações)
Para criar actions você utiliza o seguinte codigo
```bash
lb.php create action minha_action meuControlador "Documentação"
```
Onde resulta em:
```php
<?php
/**
 * MeuControlador
 * @author Nome <email@servidor.com.br>
 */
class MeuControlador_Controller extends Lb_Controllers{

    	public function init(){
    	  $this->texto = "Meu nome é:";
	    }

    public function index(){
      $this->nome = "Lucas Brito";
      	}
      	/**
      	* Documentação
      	**/
      	public function minha_action(){
      	}
}
?>
```
É também criado uma view em <code>views/MeuControlador/minha_action.phtml</code> para visualização do usuário.
Para que o usuário possa acessar a action o endereço digitado seria:<br> <code>http://localhost/public/?go=meuControlador&action=minha_action</code>

# Métodos utilizáveis tanto pelas actions,views,layout

<code><strong>$this->getControlle()</strong> => Retorna nome do controller atual</code><br>
<code><strong>$this->getAction()</strong> => Retorna nome da action atual</code><br>
<code><strong>$this->no_layout()</strong> => Diz ao código que não vai ser impresso o layout, somente a view</code><br>
<code><strong>$this->content()</strong> => Retorna o conteudo (Utilizado no Layout)</code><br>
<code><strong>$this->setHeader($tag,$href)</strong> => Seta novo elemento(script,link) no cabeçalho  (Deve-se usar o comando 'print $this->getHeader();' no layout).Ex:
```php
// Seta inclusão de código jquery.min.js que encontra-se em assets/js/jquery.min.js
$this->setHeader('script','assets/js/jquery.min.js');
// Seta inclusão do css all.css que encontra-se em assets/css/all.css
$this->setHeader('style','assets/css/all.css');
```
</code><br>
<code><strong>$this->setScript($href)</strong> => Seta novo script no cabeçalho (Deve-se usar o comando 'print $this->getHeader();' no layout)
```php
// Seta inclusão de código jquery.min.js que encontra-se em assets/js/jquery.min.js
$this->setScript('assets/js/jquery.min.js');
```
</code><br>
<code><strong>$this->setStyle($href)</strong> => Seta novo style ao cabeçalho (Deve-se usar o comando 'print $this->getHeader();' no layout)
```php
// Seta inclusão do css all.css que encontra-se em assets/css/all.css
$this->setStyle('assets/css/all.css');
```
</code><br>
<code><strong>$this->getHeader()</strong> => Retorna codigos setados no cabeçalho (Usar somente no layout)
</code><br>

<code><strong>$this->url(Array $url)</strong> => Cria e retorna url para o código.
```php
// Cria url
$url_1 = $this->url(array("controller"=>"meu_controller","action"=>"home")); 
$url_2 = $this->url(array("controller"=>"meu_controller"));
$url_3 = $this->url(array("action"=>"home"));
$url_4 = $this->url(array("controller"=>"meu_controller","action"=>"cadastro","idade"=>19,"nome"=>"Lucas","sobrenome"=>"Brito"));

// Retorna index.php?go=meu_controller&action=home (controller e "go" tem a mesma função)
print $url_1;
// Retorna index.php?go=meu_controller&action=index (Quando não enviado action, assume como index)
print $url_2;
// Retorna index.php?go=meu_controller&action=home (Quando não enviado controller assume o controller atual)
print $url_3;
// Retorna index.php?go=meu_controller&action=cadastro&idade=19&nome=Lucas&sobrenome=Brito (Quando enviado outros parametros no array, assume como novos valores do método $_GET
print $url_4;
```
</code>
<br>
<code><strong>$this->redirect($url)</strong> => Redireciona a página
```php
// Redireciona para meu_controller na action home
$this->redirect($this->url(array("controller"=>"meu_controller","action"=>"home")));
```
</code>
<br>
<code><strong>$this->_POST($name,$protect=true)</strong> | <strong>$this->_GET($name,$protect = true)</strong> => Retorna um valor POST/GET enviado pelo formulário. O protect é usuado caso não queira proteger a string(no casso de array deve-se colocar false, pois o padrão é true) contra sql injection
```php
$nome = $this->_POST("nome");
$id = $this->_GET("id");
$array = $this->_POST("array",false);
```
</code>
<br>
<code><strong>$this->set_session($name,$value)</strong> => Grava um valor na sessão do navegador
<br>
$this->get_session($name) => Retorna valor da sessão gravada no navegador
```php
// Grava sessão com o campo "nome" e seu valor "Lucas Brito"
$this->set_session("nome","Lucas Brito");
// Imprime "Meu nome é :Lucas Brito"
print "Meu nome é :".$this->get_session("nome");
```



# Criando Base(Base para consulta em tabelas do banco de dados)
s
