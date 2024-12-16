<?php
    session_start();

    if(isset($_SESSION['id'])){
        header('Location: ./dashboard/');
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <title>PharmaGuard</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="./css/popup.css">
    <script src="./js/js.js"></script>
</head>
<body>
    <!-- POPUP -->
    <div class="popin-notification" id="popin">
        <p id="popin-text"></p>
        <button onclick="closePopin()">Fechar</button>
    </div>

    <div class="page">
        <form method="POST" action="routes/formLogin.php" class="formLogin">
            <h1>Login</h1>
            <p>Digite os seus dados de acesso no campo abaixo.</p>
            <label for="user">Email</label>
            <input type="email" placeholder="Digite seu email" name="login" autofocus="true" required/>
            <label for="password">Senha</label>
            <input type="password" placeholder="Digite sua senha" name="pass" minlength="6" required/>
            <?php
                if(isset($_SESSION['log'])){
                    echo "<script >showPopin('".$_SESSION['log']."', '".$_SESSION['log1']."');</script>";
                    unset($_SESSION['log'], $_SESSION['log1']);
                }
            ?>
            <!--<a href="./">Esqueci minha senha</a>-->
            <input type="submit" value="Acessar" class="btn" />
        </form>
    </div>
    
</body>
</html>