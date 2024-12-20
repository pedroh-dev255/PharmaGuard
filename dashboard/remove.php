<?php
    session_start();

    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    require("../routes/db.php");
    date_default_timezone_set('America/Araguaina');
    if(isset($_GET['id'])){
        
        $display = $conn->query("SELECT * FROM medicamento WHERE id = ". $_GET['id']);
        $result_list = $conn->query("SELECT * FROM medicos");
    }
    if(isset($_POST['med'])){
        
        $datetime = new DateTime();
        $sql="UPDATE `medicamento` SET `retirado`='".$datetime->format('Y-m-d H:i:s')."', `solicitado`='".$_POST['med']."', `id_user` = ".$_SESSION['id']." WHERE id = " . $_GET['id'];
        if($conn->query($sql) === true){
            header("Location: ./");
        }else{
            echo "erro";
        }
       
       
    }
    $conn->close();
    $conn_press->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Adição de Medicos</title>
    <link rel="stylesheet" href="../css/dash.css">
    <link rel="stylesheet" href="../css/medicos.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body style="color: #023047; background-color:rgb(253, 203, 203);">
    <div class="page">
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
        <h1>Remover Medicamento<br>(Unidade)</h1>

        <div class="wrapper">
            <div class="table">
                <div class="row header green">
                    <div class="cell">
                        Nome
                    </div>
                    <div class="cell">
                        Princípio ativo
                    </div>
                </div>
                <?php
                if ($display->num_rows > 0) {
                    while($row = $display->fetch_assoc()) {
                        echo "
                        <div class='row'>
                            <div class='cell' data-title='Nome'>
                               ".$row['nome']."
                            </div>
                            <div class='cell' data-title='Principio ativo'>
                                ".$row["principio"]."
                            </div>
                        </div>";
                    }
                  } else {
                    echo "0 Resultados";
                  }
                    
                ?>
                
            </div>  
        </div>

        <form style="padding-top: 0px;" id="meuFormulario" action="./remove.php?id=<?php echo $_GET['id'];?>" method="post">
            <input type="text" nome="medicamento" value="<?php echo $_GET['id'];?>" style="display: none;">
            <label for="arm">Medico Solicitante:</label>
            <a href="./medicos.php">Adicionar Medico</a>
            <select id="arm" name="med" required>
                <option value=""></option>
                <?php
                if ($result_list->num_rows > 0) {
                    while($row = $result_list->fetch_assoc()) {
                    echo "<option value=".$row['id'].">".$row['nome']."</option>";
                    }
                }
                    
                ?>
            </select>
            
            <button type="submit" id ="submitBtn" >Retirar</button>
        </form>

        <div id="loading"></div>
        <footer style="display: flex; justify-content: center;">
            <vr>
            <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://phsolucoes.tech">PH Soluções</a></p></b>
        </footer>
    </div>
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