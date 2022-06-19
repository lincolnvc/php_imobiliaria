<?php
@header( "Cache-Control: max-age=604800" );

class Pagina extends Index
{
    public function __construct()
    {
        parent:: __construct();
    }
    //n�o remover o m�todo fillForm usado para preecher o form busca
    public function fillForm()
    {
        $this->fillTipo();
        $this->fillCategoria();
    }
    
    //n�o remover o m�todo welcome, a remocao causar� erro
    public function welcome()
    {
    }

    public function sobre()
    {
        $this->tpl( "public/sobre.html" );
        $this->render();
    }    

    public function links()
    {
        $this->tpl( "public/links.html" );
        $this->render();
    }    

    public function contato()
    {
        $this->tpl( "public/contato.html" );
        $this->render();
    }    
    
    
    
    /* Criando p�ginas acessiveis pelo menu */

    // O HTML deve estar dentro da pasta views/public/ de preferencia copiada do arquivo teste.html
    //exemplo site.com.br/pagina/teste/
    public function features()
    {
        // onde teste.html � o nome de um arquivo html contido em /views/public/
        $this->tpl( "public/teste.html" ); 
        $this->render();
    } 
    
    //exemplo site.com.br/pagina/teste2/
    //exemplo com o form de busca avan�ada
    //neste exemplo, repare que no teste2.html h� o include do form_busca.html
    public function teste2()
    {
        $this->tpl( "public/teste2.html" ); 
        //se voc� pretente incluir o formul�rio de busca em uma nova p�gina dever� chamar o m�todo fillForm()
        $this->fillForm(); 
        $this->render();
    }

    
    //exemplo site.com.br/pagina/demo/
    public function demo()
    {
        //o arquivo demo.html nao existe voc� pode copiar o teste.html
        $this->tpl( "public/demo.html" );
        $this->render();
    }

    //Ap�s criar os acessos voc� precisar� incluir o caminho no menu ( arquivo /public/views/header.html )
    //para os exemplos mostrados acima ficar� assim:
    //
    //    <a href="[baseUri]/pagina/demo/">DEMO</a>
    //    <a href="[baseUri]/pagina/teste1/">TESTE1</a>
    
    /* //Criando p�ginas acessiveis pelo menu */
}

/* end file */