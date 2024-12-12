<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
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
        u.usuario AS User,
        a.nome AS Armazenamento,
        md.nome AS Medico
    FROM 
        medicamento m 
    INNER JOIN 
        local a ON m.id_arm = a.id 
    LEFT JOIN
        usuarios u ON m.id_user = u.id
    INNER JOIN
        medicos md ON m.solicitado = md.id
    WHERE
        retirado IS NOT NULL
    ORDER BY
        Retirado DESC
";

    $result = $conn->query($sql);

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
<body>
    <div class="page">
        <div class="container">
            <a href="./"><img class="voltar" src="../img/12202024.png" alt="voltar"></a>
            <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
        </div>
        <div class="header">
            <h1>Lista de Remoções</h1>
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
                                ".$row['Retirado']."
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
    </div>
</body>
</html>