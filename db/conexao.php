<?php

session_start();



/*DOIS MODOS POSSÍVEIS -> local,produção */

$modo = 'local';

if($modo == 'local'){
   
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco ="sistema-login";

}

try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
   // echo "Banco conectado com sucesso!"; 
 }catch(PDOException $erro){
     echo "Falha ao se conectar com o banco! ";
 }

 function limparPost($dados){
    //Tirar espaços em brancos
    $dados = trim($dados);
    //Tirar as barras
    $dados = stripslashes($dados);
    //Tirar caracteres especiais
    $dados = htmlspecialchars($dados);

    return $dados;
 }

 function auth($tokenSessao){
    global $pdo;
    //VERIFICAR SE TEM AUTORIZAÇÃO
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($tokenSessao));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    //SE NÃO ENCONTRAR O USUÁRIO
    if(!$usuario){
     return false;
}else{
    return $usuario;
}

 }
 




