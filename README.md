# php_imobiliaria
Sistema de Imobiliária em PHP

Requisitos para Funcionamento do sistema
- Servidor Linux com cPanel
- PHP 5.2 a 5.4 (com mod_rewrite e short_open_tags habilitados)
- MySQL
- Apache 
------------------------------------------------------------------------------------------------------------------------------
INSTALAÇÃO
1 - Crie um banco de dados e um usuário para o banco MySQL, e dê todas as permissões do 
banco para o usuário criado.
2 - Abra o phpMyAdmin, selecione o banco que acaba de criar e importe a base de dados 
(BANCO-DE-DADOS.SQL) que está dentro da pasta /INSTALACAO
3 - Agora abra o arquivo database.conf.php que está dentro da pasta /app/database/
Alterar os dados conforme seu servidor
'default' => array
(
'driver'=>'mysql',
'host'=>'localhost',
'port'=>3306,
'dbname'=>'usuarioCpanel_nomeDoBanco',
'user'=>'usuarioCpanel_usuarioBanco',
'password'=>'senha'
)
4 - Agora envie todos os arquivos via FTP para o servidor (Se você enviar os arquivos via 
gerenciador de arquivos do cPanel, lembre-se de enviar o .htaccess via FTP, pois via 
gerenciados de arquivos o .htaccess perder a configuração dele). 
5 - Agora você já pode acessar o seu site.
http://www.seusite.com/PASTA/
Administração:
http://www.seusite.com/PASTA/admin
O usuário e a senha é: admin
-----------------------------------------------------------------------------------------------------------------------------
Alterar Logo e Marca D’Água
Para alterar a marca dagua, substitua a imagem images/layout/marca.png
Para alterar o Logo, substitua a imagem images/layout/bg_logo.png
Para alterar a fanpage/facebook, edite o arquivo views/public/fb.html
Para alterar o menu topo, edite o arquivo views/public/header.html
Para alterar o rodapé, edite o arquivo views/public/footer.html
Para alterar a página de Financiamento, edite o arquivo views/public/links.html
Quantidade de imóveis por página, altere no app/índex.php a linha ->paginate(15) para o 
número desejado;
Para alterar trechos do sistema, utilize o editor notepad++ . Evite usar editores que alteram 
automaticamente o encoding dos arquivos. 
Para alterar o layout, altere o css em css/public/main.css e os arquivos de views/public/*
O layout foi escrito usando o bootstrap twitter, então, quaisquer alterações você pode 
consultar o manual do bootstrap em http://getbootstrap.com/css/
NÃO HÁ SUPORTE PARA CUSTOMIZAÇÕES, APENAS PARA INSTALAÇÃO.
------------------------------------------------------------------------------------------------------------------------------
Estrutura de diretórios
- app * todos os arquivos do sistema
- app/admin * arquivos da área admin 
- app/views/public * arquivos HTML da área do usuário 
- app/views/admin * arquivos HTML da área admin 
- app/css/admin * CSS da área admin 
- app/css/public * CSS da área do usuário 
- app/images * imagens do sistema 
- app/jscripts/admin * javascripts da área admin 
- app/jscripts/public * javascript da área do usuário 
- app/files * diretório onde são criadas as pastas de usuários com arquivos
- app/class * classes do framework
--------
Outras informações:
Os sistema trabalha com URL amigáveis o que significa que não é possivel ter links como:
site.com.br/exemplo.html
Para funcionar, você precisa alterar o .htaccess contido na raiz do sistema
exemplo:
#diretorios extras
RewriteRule ^exemplo - [L,NC]
Nesse caso acima você está configurando um diretório novo na raiz do sistema, ficaria assim:
site.com.br/exemplo/qualquercoisa.html 
Ou seja os links referenciados devem estar dentro de um diretório para funcionar,
Nenhum link .html ou .php dentro da pasta /app pode ser feito, você deve
Linkar para uma pasta na raiz do sistema, ficaria assim
App
Exemplo 
- dentro do diretorio “exemplo” você coloca seu arquivo, imagens, css, etc... 
No pacote enviado tem um exemplo na pasta teste que poderá ser acessado
exemplo de acesso na localhost
//localhost/pastaDoSistema/exemplo/index.html 
Os uploads ficam por padrão no diretório /app/fotos/
Caso dê erros ao de enviar as fotos tente dar permissão de gravação neste diretório (chmod 
777)
Nunca altere o arquivo index.php da raiz, qualquer alteração implica em erro no sistema. 
Importante: No admin, para selecionar apenas uma foto para exclusão em “editar imóvel” 
basta dar duplo click;
-------------
Para Criar Novas Páginas no menu usando o sistema padrão
- Abra o arquivo app/pagina.php e siga as instruções nos comentários da classe
