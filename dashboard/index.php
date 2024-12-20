<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    
    date_default_timezone_set('America/Araguaina');

    require("../routes/db.php");
    if(isset($_GET['vencidos'])){
        $dataHoje = date("Y-m-d");
        $sql = "SELECT 
            m.id as ID,
            m.nome AS Nome,
            m.principio AS 'Principio_Ativo',
            m.validade AS Validade,
            m.adicionado AS 'Data_Adicionado',
            COUNT(*) AS Quantidade,
            m.id_arm AS id_armaze,
            a.nome AS Armazenamento 
        FROM 
            medicamento m 
        INNER JOIN 
            local a ON m.id_arm = a.id 
        WHERE
            retirado IS NULL && Validade <= '". $dataHoje ."'
        GROUP BY 
            m.nome, m.principio, m.validade, m.adicionado, a.nome
        ORDER BY 
            m.nome;
        ";
        $result = $conn->query($sql);
    }else if(isset($_POST['pesquisa'])){
        
        $pesquisa = $_POST['pesquisa'];
        if($pesquisa == "" || $pesquisa == null){
            $sql = "SELECT 
            m.id as ID,
            m.nome AS Nome,
            m.principio AS 'Principio_Ativo',
            m.validade AS Validade,
            m.adicionado AS 'Data_Adicionado',
            COUNT(*) AS Quantidade,
            m.id_arm AS id_armaze,
            a.nome AS Armazenamento 
        FROM 
            medicamento m 
        INNER JOIN 
            local a ON m.id_arm = a.id 
        WHERE
            retirado IS NULL
        GROUP BY 
            m.nome, m.principio, m.validade, m.adicionado, a.nome
        ORDER BY 
            m.nome;
        ";
            $result = $conn->query($sql);
        }else {
            $sql = "SELECT 
                m.id as ID,
                m.nome AS Nome,
                m.principio AS 'Principio_Ativo',
                m.validade AS Validade,
                m.adicionado AS 'Data_Adicionado',
                COUNT(*) AS Quantidade,
                m.id_arm AS id_armaze,
                a.nome AS Armazenamento 
            FROM 
                medicamento m 
            INNER JOIN 
                local a ON m.id_arm = a.id 
            WHERE 
                retirado IS NULL && (
                m.nome LIKE '%".$pesquisa."%' OR
                m.principio LIKE '%".$pesquisa."%' OR
                a.nome LIKE '%".$pesquisa."%')
            GROUP BY 
                m.nome, m.principio, m.validade, m.adicionado, a.nome
            ORDER BY 
                m.nome;
            ";
            $result = $conn->query($sql);
        }
       
        
    }else {
        $sql = "SELECT 
            m.id as ID,
            m.nome AS Nome,
            m.principio AS 'Principio_Ativo',
            m.validade AS Validade,
            m.adicionado AS 'Data_Adicionado',
            COUNT(*) AS Quantidade,
            m.id_arm AS id_armaze,
            a.nome AS Armazenamento 
        FROM 
            medicamento m 
        INNER JOIN 
            local a ON m.id_arm = a.id 
        WHERE
            retirado IS NULL
        GROUP BY 
            m.nome, m.principio, m.validade, m.adicionado, a.nome
        ORDER BY 
            m.nome
        LIMIT 8;
        
        ";
        $result = $conn->query($sql);
    }

    $conn->close();
    $conn_press->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dash.css">
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>PharmaGuard</title>
    <link rel="stylesheet" href="../css/popup.css">
    <script src="../js/js.js"></script>
    <style>
        /* Estilo para a caixa modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 50%;
            height: 80%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
        }

        /* Estilo para o botão de fechar */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body style="color: #023047; background-color:rgb(214, 203, 253);">
    <!-- POPUP -->
    <div class="popin-notification" id="popin">
        <p id="popin-text"></p>
        <button onclick="closePopin()">Fechar</button>
    </div>
    <div class="page">
        <?php
            if(isset($_SESSION['log'])){
                echo "<script >showPopin('".$_SESSION['log']."', '".$_SESSION['log1']."');</script>";
                unset($_SESSION['log'], $_SESSION['log1']);
            }
        ?>
        <a href="deslogar.php"><img class="deslogar" src="../img/4400828.png" alt="deslogar"></a>
        <ol class="botoes">
            <li>
                <a href="#" id="openModal" onclick="openIframe('formAdiciona.php')">Adicionar Medicamento</a>
            </li>
            <li>
                <a href="./lista_removidos.php" >Relatorio de remoções</a>
            </li>
            <li>
                <a href="./medicos.php" >Medicos</a>
            </li>
        </ol>

        <form action="./" method="post">
            <input type="search" name="pesquisa" placeholder="Nome, Princípio ativo ou Local de Armazenamento" id="search">
            <input type="submit" value="Buscar">
        </form>
        <a href="./?vencidos=true"><button type="Vencidos">Vencidos</button></a>
            

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
                    Validade
                </div>
                <div class="cell">
                    Quantidade
                </div>
                <div class="cell">
                    Armazenamento
                </div>
                <div class="cell">
                    Editar
                </div>
                <div class="cell">
                    Retirar(Un)
                </div>
                <div class="cell">
                    Retirar(Todos)
                </div>
                </div>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        $dataHoje = new DateTime(); // Obtemos a data atual

                        // Convertendo a data de validade para um objeto DateTime
                        $dataValidade = DateTime::createFromFormat('Y-m-d', $row['Validade']);
                        
                        // Verifica se a conversão foi bem-sucedida
                        if ($dataValidade instanceof DateTime) {
                            // Calcula a diferença entre as datas
                            $diferenca = $dataValidade->diff($dataHoje);
                            
                            // Obtém o número de dias na diferença
                            $diferencaDias = $diferenca->days;
                            
                            if($dataValidade <= $dataHoje){
                                $diferencaDias *= -1;
                            }
                            // Inverte o sinal da diferença para representar corretamente o número de dias até a data de validade
                            
                        
                            // Restante do seu código...
                        } else {
                            echo "Erro ao converter a data de validade.";
                        }
                        
                        
                        $vencimento = "";
                        if($diferencaDias <= 60 && $diferencaDias > 1){
                            $vencimento = "yellow";
                        }else if($diferencaDias <= 1 ){
                            $vencimento = "red";
                        }
                        
                        
                        echo "
                        <div class='row ".$vencimento."'>
                            <div class='cell' data-title='Nome'>
                               ".$row['Nome']."
                            </div>
                            <div class='cell' data-title='Principio ativo'>
                                ".$row['Principio_Ativo']."
                            </div>
                            <div class='cell' data-title='Validade'>
                                ".$dataValidade->format('d/m/Y')."
                            </div>
                            <div class='cell' data-title='Quantidade'>
                                ".$row['Quantidade']."
                            </div>
                            <div class='cell' data-title='Armazenamento'>
                                ".$row['Armazenamento']."
                            </div>
                            <div class='cell' data-title='Editar'>
                                <a class='remover_remedio' href='./formEditar.php?id=".$row['ID']."' onclick='openload()'>
                                    <img style='width: 30px' src='../img/5996831.png'>
                                </a>
                            </div>
                            
                            <div class='cell' data-title='Retirar Un'>
                                <a class='remover_remedio' href='./remove.php?id=".$row['ID']."' onclick='openload()'>
                                    <img style='width: 30px' src='../img/botao-de-menos.png'>
                                </a>
                            </div>

                            <div class='cell' data-title='Retirar Todos'>
                                <a style='align: center;' class='remover_remedio' href='./remove_todos.php?id=".$row['ID']."&qnt=".$row['Quantidade']."' onclick='openload()'>
                                    <img style='width: 30px' src='../img/clean.png'>
                                </a>
                            </div>

                        </div>";
                    }
                  } else {
                    echo "0 Resultados";
                  }
                    
                ?>
                
            </div>  
        </div>
        <br><br>
        <div id="modalOverlay"></div>

        <!-- Caixa modal -->
        <div id="modal" class="modal">
            <!-- Conteúdo da caixa modal -->
            <div class="modal-content">
                <span class="close" id="closeModal" onclick="closeIframe()">&times;</span>
                <!-- Remova o atributo src do iframe -->
                <iframe id="iframeMedicamento" frameborder="0" width="90%" height="100%"></iframe>
            </div>
        </div>
        <footer style="display: flex; justify-content: center;">
            <vr>
            <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://phsolucoes.tech">PH Soluções</a></p></b>
        </footer>
    </div>

    <script>
        // Obtém o link de "Adicionar Medicamento" e a caixa modal
        const openModal = document.getElementById('openModal');
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('closeModal');

        // Adiciona um ouvinte de evento para o link de "Adicionar Medicamento"
        openModal.addEventListener('click', function(event) {
            // Previne o comportamento padrão de um link
            event.preventDefault();
            // Exibe a caixa modal
            modal.style.display = 'block';
        });

        // Adiciona um ouvinte de evento para o botão de fechar
        closeModal.addEventListener('click', function() {
            // Oculta a caixa modal
            modal.style.display = 'none';
        });

         // Função para definir a URL do iframe e exibir a caixa modal
        function openIframe(src) {
            // Obtém o iframe
            const iframeMedicamento = document.getElementById('iframeMedicamento');
            // Define a origem do iframe
            iframeMedicamento.src = src;
            // Exibe a caixa modal
            modal.style.display = 'block';
        }

        // Função para remover a origem do iframe e fechar o modal
        function closeIframe() {
            // Obtém o iframe
            const iframeMedicamento = document.getElementById('iframeMedicamento');
            // Remove a origem do iframe
            iframeMedicamento.src = '';
            // Oculta a caixa modal
            modal.style.display = 'none';
        }

        function openload() {
        // Exibe o modal
        document.getElementById("modalOverlay").style.display = "block";

        // Impede o clique em outras partes da página
        document.body.style.pointerEvents = "none";
    }
    
    </script>

</body>
</html>
