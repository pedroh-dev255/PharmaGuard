<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
        exit;
        
    }
    
    if($_SESSION['id'] != 1 && $_SESSION['id'] != 9){
        header("Location: ./");
        exit;
    }
    
    require_once("../routes/db.php");
    if(isset($_POST['nome']) && $_POST['nome'] != ""){
        

        $sql="INSERT INTO usuarios(usuario,senha) VALUES ('".$_POST['nome']."', '".$_POST['senha']."')";
        if($conn->query($sql) === true){
            $_SESSION['log'] = "<p class='sucesso'>Ususario adicionado com sucesso!</p>";
            header("Reload");
        }else{
            $_SESSION['log'] = "<p class='erro'>Erro ao adicionar usuario!</p>";
            header("Reload");
        }
        
    }
    $lista = $conn->query("select * from usuarios");
    $conn->close();
    
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Adição de Medicos</title>
    <link rel="stylesheet" href="../css/medicos.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php
    if(isset($_SESION['log'])){
        echo $_SESSION['log'];
        unset($_SESSION['log']);
    }
    ?>
    <div class="container">
        <a href="./"><img class="voltar" src="../img/12202024.png" alt="voltar"></a>
        <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
    </div>
    <form id="meuFormulario" action="./usuarios.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" minlength="6" id="senha" required>
        
        <button type="submit" id ="submitBtn" >Adicionar</button>
    </form>

    <div id="loading"></div>

    <?php
        if (isset($lista) && $lista->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>USER</th><th>SENHA</th></tr>";
            // output data of each row
            while($row = $lista->fetch_assoc()) {
              echo "<tr><td>".$row["id"]."</td><td>".$row["usuario"]."</td><td>".$row["senha"]."</td></tr>";
            }
            echo "</table>";
          } else {
            echo "0 results";
          }
    ?>

    <script>
        document.getElementById("meuFormulario").addEventListener("submit", function() {
            // Exibir animação de loading
            document.getElementById("loading").style.display = "block";

            // Desativar o botão de envio
            document.getElementById("submitBtn").disabled = true;
        });
    </script>
</body>
</html>
