
<?php require('../db/conexao.php'); 

//VERIFICAR SE A POSTAGEM EXISTE DE ACORDO COM OS CAMPOS
if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['repete_senha'])){
   
    //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
    if(empty($_POST['nome']) or empty($_POST['email']) or empty($_POST['senha']) or empty($_POST['repete_senha']) or empty($_POST['termos'])){


        $erro_geral = "Todos os campos são obrigatórios";

    }else{

        //RECEBER VALORES VINDOS DO POST E LIMPAR
        $nome = limparPost($_POST['nome']);
        $email = limparPost($_POST['email']);
        $senha = limparPost($_POST['senha']);
        $senha_cript = sha1($senha);
        $repete_senha = limparPost($_POST['repete_senha']);
        $checkbox = limparPost($_POST['termos']);
        
        //VERIFICAR SE NOME É APENAS LETRAS E ESPAÇOS
        if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
            $erro_nome = "Somente permitido letras e espaços em branco!";
        }

        //VERIFICAR SE EMAIL É VÁLIDO
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro_email = "Formato de e-mail inválido!";
        }
        //VERIFICAR SE SENHA TEM 6 DIGITOS OU MAIS
        if(strlen($senha) < 6 ){
            $erro_senha = "Numero mínimo de 6 digitos";
        }
        //VERIFICAR SE SENHA SÃO IGUAIS
        if($repete_senha !== $senha){
            $erro_repete_senha = "As senhas devem ser iguais";
        }

        //VERIFICAR SE CHECKBOX FOI MARCADO
        if($checkbox !=="ok"){
            $erro_checkbox = "Desativado";
        }

        if(!isset($erro_geral) && !isset($erro_nome) && !isset($erro_email) && !isset($erro_senha) && !isset($erro_repete_senha) && !isset($erro_checkbox)){
           //VERIRIFCAR SE ESTE EMAIL JÁ ESTÁ CADASTRO NO BANCO
           $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=?  LIMIT 1");
           $sql->execute(array($email));
           $usuario = $sql->fetch();
           //SE NÃO EXISTIR O USUARIO - ADCIONAR NO BANCO
           if(!$usuario){
            //CADASTRAR NO BANCO
            $recupera_senha="";
            $token="";
            $status ="novo";
            $data_cadastro = date('d/m/Y');
            $sql = $pdo-> prepare("INSERT INTO usuarios VALUES(null,?,?,?,?,?,?,?)");
            if($sql-> execute(array($nome,$email,$senha_cript,$recupera_senha,$token,$status,$data_cadastro))){
              //SE O MODO FOR LOCAL
                if($modo == "local"){
                    header('location: ../index.php?result=ok');      
                }
            
           }
            
           }else{
            //JÁ EXISTE USUARIO APRESENTAR ERRO
             $erro_geral = "Usuário já cadastrado";
           }

        }
    }    
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilo.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  
    <title>Cadastrar</title>
</head>
<body>
    <form method="post">
        <h1>Cadastrar</h1>
       
        <?php if (isset($erro_geral)){ ?>
         <div class='erro_geral animate__animated animate__rubberBand'>
         <?php  echo $erro_geral; ?>
         </div> 
        <?php } ?>

         <div class="input-group">
            <img class="icon" src="../img/nome.png">
           <input <?php if (isset($erro_geral) or isset($erro_nome)) {echo "class='erro_input'";} ?>  type="text" name="nome"  placeholder="Nome completo" <?php if(isset($_POST['nome'])){ echo "value='".$_POST['nome']."'";}?> autoComplete='off'>
        
        <?php if(isset($erro_nome)){ ?>
            <div class="falhou">
               <?php echo $erro_nome; ?>
            </div>
        <?php } ?>  
        
        
        
        </div>
        
        <div class="input-group"><img class="icon" src="../img/senha.png" >
            <input <?php if (isset($erro_geral) or isset($erro_email)) {echo "class='erro_input'";}?> type="email" name="email"  placeholder="Digite seu melhor email"  <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";}?>  autoComplete="off">

            <?php if (isset($erro_email)) { ?>
            <div class="falhou">
               <?php echo $erro_email; ?>
            </div>
        <?php } ?>
        </div> 
        

        <div class="input-group">
            <img class="icon" src="../img/trancar.png">
             <input <?php if (isset($erro_geral) or isset($erro_senha)) {echo "class='erro_input'";}?> type="password" name="senha" placeholder="Senha de no mínimo 6 digistos" <?php if(isset($_POST['senha'])){ echo "value='".$_POST['senha']."'";}?>>

             <?php if (isset($erro_senha)) { ?>
              <div class="falhou">
               <?php echo $erro_senha; ?>
              </div>
             <?php } ?>
        </div>

        <div class="input-group">
            <img class="icon" src="../img/desbloquear.png" >
        <input <?php if (isset($erro_geral) or isset($erro_repete_senha)) {echo "class='erro_input'";}?>  type="password" name="repete_senha"  placeholder="Repita a senha" <?php if(isset($_POST['repete_senha'])){ echo "value='".$_POST['repete_senha']."'";}?>>

            <?php if (isset($erro_repete_senha)) { ?>
              <div class="falhou">
               <?php echo $erro_repete_senha; ?>
              </div>
             <?php } ?>

            
        </div>

        <div <?php if (isset($erro_geral) or isset($erro_checkbox)){echo "class='erro_input'";}else{echo'class="input-group"';}?>  >
           
        <input  type="checkbox" name="termos" id="termos" value="ok" required > 
        <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link"href="#">Politica de privacidade</a>
        e os <a class="link" href="#">termos de uso</a></label>
        </div>

       
        <button class="btn-blue" type="submit">Cadastrar</button>
        <a href="../index.php">Já tenho uma conta</a>
    </form>
</body>
</html>