<?php

require('db/conexao.php');

if (isset($_POST['email']) && isset($_POST['senha']) && !empty($_POST['email']) && !empty($_POST['senha'])){

// RECEBER OS DADOS VINDOS DO POST E LIMPAR
$email = limparPost($_POST['email']);
$senha = limparPost($_POST['senha']);
$senha_cript = sha1($senha);


//VERIFICAR SE EXISTE ESTE USUÁRIO NO BANCO

$sql = $pdo ->prepare("SELECT * FROM usuarios WHERE email=? AND senha=?  LIMIT 1");
$sql->execute(array($email,$senha_cript));
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if($usuario){
        //EXISTE O USUARIO
        //CRIAR UM TOKEN
        $token = uniqid().date('d-m-Y-H-i-s');

     
        //ATUALIZAR O TOKEN DESTE USUARIO NO BANCO

        $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
       if($sql->execute(array($token,$email,$senha_cript))){
       //ARMAZENAR ESTE TOKEN NA SESSAO (SESSION)
       $_SESSION['TOKEN'] = $token;
       header('location: routes/restrita.php');
   }

}else{
    $erro_login = "Usuário ou senha incorreto";
}

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilo.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h1>Login</h1>
        

        <?php if (isset($_GET['result']) && isset($_GET['result']) == 'ok') { ?>
            <div class="sucesso animate__animated animate__rubberBand">Cadastrado com sucesso!</div>
        <?php } ?>

        <?php if (isset($erro_login)){ ?>
            <div class='erro_geral animate__animated animate__rubberBand'>
            <?php  echo $erro_login; ?>
            </div> 
        <?php } ?>
        

        <div class="input-group">
            <img class="icon" src="img/senha.png" alt="">
        <input  type="email" name="email"  placeholder="Digite seu email" autoComplete='off' required>
        </div>
        
        <div class="input-group"><img class="icon" src="img/trancar.png" >
            <input type="password" name="senha"  placeholder="Digite sua senha" required>
        </div>
        
        <button class="btn-blue" type="submit">Fazer Login</button>
        <a href="routes/cadastrar.php">Ainda não tenho cadastro</a>
    </form>

    <?php if (isset($_GET['result']) && ($_GET['result'] == 'ok')) { ?>
    <script>
       setTimeout(() => {
        var sucesso = document.querySelector('.sucesso');
        sucesso.style.display = 'none';
       }, 5000);
       <?php $_GET['result'] = ''; ?>
    </script>
    
 <?php } ?>
    
 <?php  if(isset($erro_login)){ ?>
    <script>
       setTimeout(() => {
        var erro_geral = document.querySelector('.erro_geral');
        erro_geral.style.display = 'none';
       }, 5000);
    </script>

<?php } ?>
</body>
</html>