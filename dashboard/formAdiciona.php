<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    require_once("../routes/db.php");
    $result_list = $conn->query("SELECT * FROM local");

    $conn->close();
    $conn_press->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Adição de Medicamento</title>
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/formAdicionar.css">
    <link rel="stylesheet" href="../css/popup.css">
    <script src="../js/js.js"></script>
</head>
<body>
    <!-- POPUP -->
    <div class="popin-notification" id="popin">
        <p id="popin-text"></p>
        <button onclick="closePopin()">Fechar</button>
    </div>
    <?php
        if(isset($_SESSION['log'])){
            echo "<script >showPopin('".$_SESSION['log']."', '".$_SESSION['log1']."');</script>";
            unset($_SESSION['log'], $_SESSION['log1']);
        }
    ?>
    <form id="meuFormulario" action="../routes/formAdicionar.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="principio">Princípio Ativo:</label>
        <input type="text" name="principio" id="principio" required>

        <label for="data-val">Data de Validade:</label>
        <input type="date" name="data-val" id="data-val" required>

        <label for="quant">Quantidade:</label>
        <input type="number" name="quant" id="quant" required>

        <label for="arm">Armazenamento:</label>
        <?php
            if($_SESSION['nivel'] == 'admin'){
                echo '<a href="./formAdLocal.php">Adicionar local</a>';
            }
        ?>
        <select id="arm" name="arm" required>
            <option value=""></option>
            <?php
            if ($result_list->num_rows > 0) {
                while($row = $result_list->fetch_assoc()) {
                  echo "<option value=".$row['id'].">".$row['nome']."</option>";
                }
              }
            
            ?>
        </select>
        
        <button type="submit" id ="submitBtn" >Adicionar</button>
    </form>
    <div id="loading"></div>

    <footer style="display: flex; justify-content: center;">
        <vr>
        <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://www.phsolucoes.site/">PH Soluções</a></p></b>
    </footer>

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
