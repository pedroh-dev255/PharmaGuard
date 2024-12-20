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
        $cod= "UPDATE `medicamento` SET `retirado`='".$datetime->format('Y-m-d H:i:s')."', `solicitado`='".$_POST['med']."', `id_user` = ".$_SESSION['id']." WHERE nome = '".$_POST['nome']."'  AND principio = '".$_POST['principio']."' AND validade = '".$_POST['validade']."' AND adicionado = '".$_POST['adicionado']."' AND id_arm = ".$_POST['arm']. " AND retirado IS NULL AND solicitado IS NULL";
        $updater = $conn->query($cod);

        if($updater === false){
            $_SESSION['log'] = "Erro na retirada dos medicamentos :(";
            $_SESSION['log1'] = "error"; // success , warning, error
            header("Location: ../dashboard/");
            exit();
        }
        

        header("Location: ../dashboard/");
        $_SESSION['log'] = "Medicamentos Removidos com Sucesso!";
        $_SESSION['log1'] = "success"; // success , warning, error
        exit();
       
       
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
<body style="color: #023047; background-color:rgb(255, 161, 161);">
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
        <h1>Remover Medicamentos<br>(Todos do Lote)</h1>

        <div class="wrapper">
            <div class="table">
                <div class="row header green">
                    <div class="cell">
                        Nome
                    </div>
                    <div class="cell">
                        Princípio ativo
                    </div>
                    <div class="cell">
                        Quantidade
                    </div>
                </div>
                <?php
                if ($display->num_rows > 0) {
                    $row = $display->fetch_assoc();
                        echo "
                        <div class='row'>
                            <div class='cell' data-title='Nome'>
                               ".$row['nome']."
                            </div>
                            <div class='cell' data-title='Principio ativo'>
                                ".$row["principio"]."
                            </div>
                            <div class='cell' data-title='Quantidade'>
                                ".$_GET['qnt']."
                            </div>
                        </div>";
                    
                  } else {
                    echo "0 Resultados";

                    //header("Location: ../dashboard/");
                    $_SESSION['log'] = "Erro, medicamento não existe";
                    $_SESSION['log1'] = "error"; // success , warning, error

                    //exit();
                  }
                    
                ?>
                
            </div>  
        </div>

        <form style="padding-top: 0px;" id="meuFormulario" action="./remove_todos.php?id=<?php echo $_GET['id'];?>&qnt=<?php echo $_GET['qnt'];?>" method="post">
            <input type="text" name="nome" value="<?php echo $row['nome'];?>" style="display: none;">
            <input type="text" name="principio" value="<?php echo $row['principio'];?>" style="display: none;">
            <input type="text" name="validade" value="<?php echo $row['validade'];?>" style="display: none;">
            <input type="text" name="adicionado" value="<?php echo $row['adicionado'];?>" style="display: none;">
            <input type="text" name="arm" value="<?php echo $row['id_arm'];?>" style="display: none;">
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
            
            <button type="submit" id ="submitBtn" >Retirar Todos</button>
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