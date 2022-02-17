
# Desafio T�cnico Desenvolvimento

Esta � uma interface para controle de vendas, com importa��o de planilhas,  que tem a fun��o de contemplar o Desafio T�cnico Desenvolvimento da empresa Face Digital.

## Informa��es sobre a implementa��o

Para o desenvolvimento do fluxo de vendas, foi utilizada a linguagem PHP, com framework Codeigniter 3. Bancos de dados MySql.
O frontend foi desenvolvido com aux�lio do template Bootstrap Admin LTE 3, e javascript com JQuery. 
PLanilhas com tamanhos inferiores a 5mb podem ser enviadas.

## Bibliotecas de apoio

Para acesso por REST Api, foi empregada a biblioteca REST_Controller.
Valida��es de dados utilizam apoio da biblioteca Brazanation, para CNPJ.
A leitura e itera��o de dados de planilhas utiliza a biblioteca PHPOffice/PhpSpreadsheet.
Para ter um controle �nico atrav�s de UUIDV4 das importa��es de dados realizadas � utilizada a biblioteca ramsey/Uuid. 

## Pr�-requisitos

 - PHP 7.4
 - MySql
 - Git
 - Composer

## Instala��o
Efetue o clone da aplica��o no seu diret�rio de acesso web:

    git clone https://github.com/frezzatony/desafio-face-digital
Permita que a sua execu��o php tenha permiss�es de escrita na pasta application/uploads e todas suas filhas.

Na raiz da aplica��o, instale as bibliotecas atrav�s do composer:

    composer install
    
� fornecido o arquivo database.example.php, dentro do diret�rio application/config. Renomeie-o para database.php e insira a configura��o de conex�o com seu banco de dados MySql.

A as migrations do banco de dados podem ser executadas no pr�prio navegador acessando a o caminho da aplica��o /install, exemplo:

    https://127.0.0.1/install
Ap�s ser exibida a confirma��o de migration do banco de dados a aplica��o estar� pronta para ser executada.

Utilize o caminho web raiz da aplica��o para ter acesso ao projeto, exemplo:

    https://127.0.0.1
