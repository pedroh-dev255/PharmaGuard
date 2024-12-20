<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    
    if(!isset($_GET['id']) || $_GET['id']==""){
        header("Location: ./");
    }

    require("../routes/db.php");

    $sql = "
    SELECT 
        m.id as ID,
        m.nome AS Nome,
        m.principio AS 'Principio_Ativo',
        m.validade AS Validade,
        m.retirado AS 'Retirado',
        m.id_arm AS id_armaze,
        m.solicitado AS Solicitante,
        m.id_user AS User_id,
        a.nome AS Armazenamento
    FROM 
        medicamento m 
    INNER JOIN 
        local a ON m.id_arm = a.id 
    WHERE
        m.id = ".$_GET['id'];

    $result = $conn->query($sql);
    
    $result_up = $conn->query($sql);
    
    $result_list = $conn->query("SELECT * FROM local");
    
    
    if(isset($_POST['nome'])){
        
        if (isset($result_up) && $result_up->num_rows > 0) {
            while($row = $result_up->fetch_assoc()) {
                $nomeOld = $row['Nome'];
                $principioOld = $row['Principio_Ativo'];
                $localOld = $row['id_armaze'];
                $validadeOld = $row['Validade'];
            }
        }
        
        echo $sql="UPDATE `medicamento` SET `nome`='".$_POST['nome']."',`principio`='".$_POST['principio']."',`validade`='".$_POST['data-val']."',`id_arm`=".$_POST['arm'].",`id_user`=".$_SESSION['id']." WHERE `nome` = '".$nomeOld."' AND `principio` = '".$principioOld."' AND  `validade`= '".$validadeOld."' AND `id_arm`= ".$localOld;
        if($conn->query($sql) === true){
            header("Location: ./");
        }
        
    }
    

    $conn->close();
    $conn_press->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/medicos.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <title>Editar</title>
    <style>
        
  .wrapper {
    margin: 0 auto;
    padding-top: 10px;
    max-width: 80%;
  }
  
  .table {
    margin: 0 0 40px 0;
    width: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    display: table;
  }
  @media screen and (max-width: 580px) {
    .table {
      display: block;
    }
  }
  
  .row {
    display: table-row;
    background: #f6f6f6;
  }
  .row:nth-of-type(odd) {
    background: #e9e9e9;
  }
  .row.header {
    font-weight: 900;
    color: #ffffff;
    background: #ea6153;
  }
  .row.green {
    background: #27ae60;
  }
  .row.blue {
    background: #2980b9;
  }
  @media screen and (max-width: 580px) {
    .row {
      padding: 14px 0 7px;
      display: block;
    }
    .row.header {
      padding: 0;
      height: 6px;
    }
    .row.header .cell {
      display: none;
    }
    .row .cell {
      margin-bottom: 10px;
    }
    .row .cell:before {
      margin-bottom: 3px;
      content: attr(data-title);
      min-width: 98px;
      font-size: 10px;
      line-height: 10px;
      font-weight: bold;
      text-transform: uppercase;
      color: #969696;
      display: block;
    }
  }
  
  .cell {
    padding: 6px 12px;
    display: table-cell;
  }
  @media screen and (max-width: 580px) {
    .cell {
      padding: 2px 16px;
      display: block;
    }
  }
  
  .row.yellow {
    background-color: #ffff00; /* Amarelo */
}

.row.red {
    background-color: #ff0000; /* Vermelho */
}

  .container {
    width: 100%;
    text-align: center; /* Centraliza os elementos dentro do container */
}
        
    </style>
</head>
<body style="color: #023047; background-color:rgb(203, 253, 207);">
    <div class="page">
        <div class="container">
            <a href="./"><img class="voltar" src="../img/12202024.png" alt="voltar"></a>
            <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
        </div>
        <div class="header" align="center">
            <h1>Editar Lançamento</h1>
        </div>
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
                        Armazenamento
                    </div>
                    <div class="cell">
                        Validade
                    </div>
                </div>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $data_val = DateTime::createFromFormat('Y-m-d', $row['Validade']);
                        $nome = $row['Nome'];
                        $principio = $row['Principio_Ativo'];
                        $local = $row['id_armaze'];
                        $validade = $row['Validade'];
                        echo "
                        <div class='row'>
                            <div class='cell' data-title='Nome'>
                               ".$row['Nome']."
                            </div>
                            <div class='cell' data-title='Principio ativo'>
                                ".$row['Principio_Ativo']."
                            </div>
                            <div class='cell' data-title='Armazenamento'>
                                ".$row['Armazenamento']."
                            </div>
                            <div class='cell' data-title='validade'>
                                ".$row['Validade']."
                            </div>
                        </div>";
                    }
                  } else {
                    echo "0 Resultados";
                  }
                    
                ?>
                
            </div>  
        </div>
        <form id="meuFormulario" action="./formEditar.php?id=<?php echo $_GET['id'];?>" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo $nome;?>" required>
    
            <label for="principio">Princípio Ativo:</label>
            <input type="text" name="principio" id="principio" value="<?php echo $principio;?>" required>
    
            <label for="data-val">Data de Validade:</label>
            <input type="date" name="data-val" id="data-val" value="<?php echo $validade;?>" required>

            <label for="arm">Armazenamento:</label>
            <select id="arm" name="arm" required>
                <option value=""></option>
                <?php
                
                if ($result_list->num_rows > 0) {
                    while($row = $result_list->fetch_assoc()) {
                        $sel ="";
                        if($local == $row['id']){
                            $sel = " selected";
                        }
                      echo "<option value=".$row['id']." ".$sel.">".$row['nome']."</option>";
                    }
                  }
                
                ?>
            </select>
            
            <button type="submit" id ="submitBtn" >Editar</button>
        </form>
        <div id="loading"></div>

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
            <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://phsolucoes.tech">PH Soluções</a></p></b>
        </footer>
    </div>
</body>
</html>