<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }

    require("../routes/db.php");

    if(isset($_GET['data1']) && isset($_GET['data2'])){
        $usuariof = "";
        if($_GET['user'] != 0){
            $usuariof = "AND Id_User = ".$_GET['user'];
        }

        $medicof = "";
        if($_GET['medico'] != 0){
            $medicof = "AND md.id = ".$_GET['medico'];
        }
        $sql = "
        SELECT 
            m.id as ID,
            m.nome AS Nome,
            m.principio AS 'Principio_Ativo',
            m.validade AS Validade,
            m.retirado AS 'Retirado',
            m.id_arm AS id_armaze,
            m.solicitado AS Solicitante,
            m.id_user AS Id_User,
            a.nome AS Armazenamento,
            md.nome AS Medico
        FROM 
            medicamento m 
        INNER JOIN 
            local a ON m.id_arm = a.id 
        INNER JOIN
            medicos md ON m.solicitado = md.id
        WHERE
            retirado IS NOT NULL AND
            retirado BETWEEN '$_GET[data1] 00:00:00' AND '$_GET[data2] 23:59:59'
            $usuariof $medicof
        ORDER BY
            Retirado DESC
    ";
    } else {
        $sql = "
            SELECT 
                m.id as ID,
                m.nome AS Nome,
                m.principio AS 'Principio_Ativo',
                m.validade AS Validade,
                m.retirado AS 'Retirado',
                m.id_arm AS id_armaze,
                m.solicitado AS Solicitante,
                m.id_user AS Id_User,
                a.nome AS Armazenamento,
                md.nome AS Medico
            FROM 
                medicamento m 
            INNER JOIN 
                local a ON m.id_arm = a.id 
            INNER JOIN
                medicos md ON m.solicitado = md.id
            WHERE
                retirado IS NOT NULL
            ORDER BY
                Retirado DESC
        ";
    }
    

    $result = $conn->query($sql);
    $result_med = $conn->query("SELECT * FROM medicos");

    $user_press = $conn_press->query("SELECT id,name FROM Users");

    $conn->close();
    $conn_press->close();
    $u=0;
    while($nome = $user_press->fetch_assoc()){
        $u++;
        $u_id[$u]=$nome['id'];
        $u_nome[$u]=$nome['name'];
    }
    $t=$u;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/dash.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <title>Lista de Remoções</title>
</head>
<body style="color: #023047; background-color:rgb(203, 253, 249);">
    <div class="page">
        <div class="container">
            <a href="./"><img class="voltar" src="../img/12202024.png" alt="voltar"></a>
            <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
        </div>
        <div class="header">
            <h1>Lista de Remoções</h1>
        </div>

        <form action="./lista_removidos.php" method="get">
            <input type="date" name="data1" value="<?php if(isset($_GET['data1'])){ echo $_GET['data1']; }else{ echo date('Y-m-d', strtotime('-30 days'));}?>" required>
            <input type="date" name="data2" value="<?php if(isset($_GET['data2'])){ echo $_GET['data2']; }else{ echo date('Y-m-d', time());}?>" required>
            <select name="user" required>
                <option value="0" <?php if(isset($_GET['user']) && $_GET['user'] == 0){echo "selected";}?>>Todos</option>
                <?php
                    for($j=1;$j<=$t;$j++){
                        $sel="";
                        if(isset($_GET['user']) && $_GET['user'] == $u_id[$j]){
                            $sel = " SELECTED";
                        }
                        echo "<option value='$u_id[$j]' $sel>$u_nome[$j]</option>";
                    }
                ?>
            </select>

            <select name="medico" required>
                <option value="0" <?php if(isset($_GET['medico']) && $_GET['medico'] == 0){echo "selected";}?>>Todos</option>
                <?php
                    while($row_med = $result_med->fetch_assoc()){
                        $sel="";
                        if(isset($_GET['medico']) && $_GET['medico'] == $row_med['id']){
                            $sel = " SELECTED";
                        }
                        echo "<option value='$row_med[id]' $sel>$row_med[nome]</option>";
                    }
                ?>
            </select>
            
            <button type="submit">Filtrar</button>
        </form>
        <a href="./lista_removidos.php"><button alt="limpar filtro">X</button></a>
        <br>
        <button onclick="window.print()">Imprimir</button>
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
                        Retirado
                    </div>
                    <div class="cell">
                        Solicitante
                    </div>
                    <div class="cell">
                        Usuario
                    </div>
                </div>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $data_val = DateTime::createFromFormat('Y-m-d', $row['Validade']);
                        
                        for($i=1;$i<=$u;$i++){
                            if($u_id[$i] == $row['Id_User']){
                                $row['User'] = $u_nome[$i];
                            }
                        }

                        if(!isset($row['User'])){
                            $row['User'] = 'N/A';
                        }

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
                            <div class='cell' data-title='Retirado'>
                                ".date('H:i d/m/Y', strtotime($row['Retirado']))."
                            </div>
                            <div class='cell' data-title='Solicitante'>
                                ".$row['Medico']."
                            </div>
                            <div class='cell' data-title='Usuario'>
                                ".$row['User']."
                            </div>
                        </div>";
                    }
                  } else {
                    echo "0 Resultados";
                  }
                    
                ?>
                
            </div>  
        </div>
        <footer style="display: flex; justify-content: center;">
            <vr>
            <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://www.phsolucoes.site/">PH Soluções</a></p></b>
        </footer>
    </div>
</body>
</html>