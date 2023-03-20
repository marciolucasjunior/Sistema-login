<?php
require('../db/conexao.php');

//VERIFICAÇÃO DE AUTENTICAÇÃO
$user = auth($_SESSION['TOKEN']);

if($user){
    global $user;
   
}else{
    //REDIRECIONAR PARA LOGIN
    header('location: ../index.php');
}


/*
//VERIFICAR SE TEM AUTORIZAÇÃO PARA LOGAR
$sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
$sql->execute(array($_SESSION['TOKEN']));
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

//SE NÃO ENCONTRAR O USUÁRIO
if(!$usuario){
    header('location: ../index.php');
}else{
    echo "<h1> SEJA BEM-VINDO <b style='color:red'>".$usuario['nome']."!</b></h1>";
    echo "<br><br>";
    echo "<a style='background :green ;color: white ;padding: 20px; border-radius:5px; text-decoration:none;' href='logout.php'>Sair do sistema</a>";
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/restrita.css">
    <title>Document</title>
</head>
<body>
   
    <div class="container_restrita">
        <h1>Seja bem vindo <?php echo $user['nome'] ; ?>!</h1>
        <p>Você esta na página restrita , somente para quem esta logado!</p>
        <br><br>
        <?php echo"<a class='btn-green' href='logout.php'>Encerrar sessão</a>";?>
    </div>
</body>
</html>