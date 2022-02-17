
# Desafio Técnico Desenvolvimento

Esta é uma interface para controle de vendas, com importação de planilhas,  que tem a função de contemplar o Desafio Técnico Desenvolvimento da empresa Face Digital.

## Informações sobre a implementação

Para o desenvolvimento do fluxo de vendas, foi utilizada a linguagem PHP, com framework Codeigniter 3. Bancos de dados MySql.
O frontend foi desenvolvido com auxílio do template Bootstrap Admin LTE 3, e javascript com JQuery. 
PLanilhas com tamanhos inferiores a 5mb podem ser enviadas.

## Bibliotecas de apoio

Para acesso por REST Api, foi empregada a biblioteca REST_Controller.
Validações de dados utilizam apoio da biblioteca Brazanation, para CNPJ.
A leitura e iteração de dados de planilhas utiliza a biblioteca PHPOffice/PhpSpreadsheet.
Para ter um controle único através de UUIDV4 das importações de dados realizadas é utilizada a biblioteca ramsey/Uuid. 

## Pré-requisitos

 - PHP 7.4
 - MySql
 - Git
 - Composer

## Instalação
Efetue o clone da aplicação no seu diretório de acesso web:

    git clone https://github.com/frezzatony/desafio-face-digital
Permita que a sua execução php tenha permissões de escrita na pasta application/uploads e todas suas filhas.

Na raiz da aplicação, instale as bibliotecas através do composer:

    composer install
    
É fornecido o arquivo database.example.php, dentro do diretório application/config. Renomeie-o para database.php e insira a configuração de conexão com seu banco de dados MySql.

A as migrations do banco de dados podem ser executadas no próprio navegador acessando a o caminho da aplicação /install, exemplo:

    https://127.0.0.1/install
Após ser exibida a confirmação de migration do banco de dados a aplicação estará pronta para ser executada.

Utilize o caminho web raiz da aplicação para ter acesso ao projeto, exemplo:

    https://127.0.0.1
