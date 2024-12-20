<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    require_once("../routes/db.php");
    if(isset($_POST['nome']) && $_POST['nome'] != ""){
        

        $sql="INSERT INTO medicos(nome) VALUES ('".$_POST['nome']."')";
        if($conn->query($sql) === true){
            $_SESSION['log'] = "<p class='sucesso'>Medico adicionado com sucesso!</p>";
            header("Reload");
        }else{
            $_SESSION['log'] = "<p class='erro'>Erro ao adicionar medico!</p>";
            header("Reload");
        }
        
    }
    $lista = $conn->query("select * from medicos");
    $conn->close();
    $conn_press->close();
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
<body style="color: #023047; background-color:rgb(252, 253, 203);">
    <?php
    if(isset($_SESION['log'])){
        echo $_SESSION['log'];
        unset($_SESSION['log']);
    }
    ?>
    <div class="container">
        <a href="./"><img class="voltar" src="../img/12202024.png" alt="voltar"></a>
        <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
    
        <form id="meuFormulario" action="./medicos.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
            
            <button type="submit" id ="submitBtn" >Adicionar</button>
            <br><br><br><br>
            <?php
            if (isset($lista) && $lista->num_rows > 0) {
                echo "<table><tr><th>ID</th><th>Name</th></tr>";
                // output data of each row
                while($row = $lista->fetch_assoc()) {
                  echo "<tr><td>".$row["id"]."</td><td>".$row["nome"]."</td></tr>";
                }
                echo "</table>";
              } else {
                echo "0 results";
              }
        ?>
        </form>
    
        <div id="loading"></div>
    
        
    </div>
    <script>
        document.getElementById("meuFormulario").addEventListener("submit", function() {
            // Exibir animação de loading
            document.getElementById("loading").style.display = "block";

            // Desativar o botão de envio
            document.getElementById("submitBtn").disabled = true;
        });
    </script>

    <footer style="display: flex; justify-content: center;">
        <vr>
        <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://www.phsolucoes.site/">PH Soluções</a></p></b>
    </footer>
</body>
</html>
