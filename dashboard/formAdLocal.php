<?php
    session_start();
    
    if(!isset($_SESSION['id']) && !isset($_SESSION['nivel'])){
        header("Location: ../index.php");
    }

    if($_SESSION['nivel'] != 'admin'){
        $_SESSION['log'] = "Sem acesso!";
        $_SESSION['log1'] = "warning"; // success , warning, error
        header("Location: ./");
        exit();
    }

    
    if(isset($_POST['nome_lo'])){
        require("../routes/db.php");

        if ($conn->query("INSERT INTO `local`(`nome`) VALUES ('".$_POST['nome_lo']."')") === TRUE) {
            $_SESSION['log'] = "local cadastrado";
            $_SESSION['log1'] = "success"; // success , warning, error
            header("Location: ../dashboard/formAdiciona.php");
            exit();
        } else {
            $_SESSION['log'] = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['log1'] = "error"; // success , warning, error
            header("Location: ../dashboard/formAdLocal.php");
            exit();
        }
        $conn->close();
        $conn_press->close();
    }

    
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Adição de Local</title>
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

    <div class="container">
        <?php
            if(isset($_SESSION['log'])){
                echo "<script >showPopin('".$_SESSION['log']."', '".$_SESSION['log1']."');</script>";
                unset($_SESSION['log'], $_SESSION['log1']);
            }
        ?>
        <form action="./formAdLocal.php" method="post" style="height: 60vh;">
            <label for="nome">Nome:</label>
            <input type="text" name="nome_lo" id="nome" required>
            
            <input type="submit" value="Adicionar">
        </form>

        <table>
            <tr>
                <th>Id</th>
                <th>Local</th>
            </tr>
            <?php
                require("../routes/db.php");
                $result = $conn->query("SELECT * FROM local");
                $conn->close();

                while($row = $result->fetch_assoc()){
                    echo "<tr><td>".$row['id']."</td><td>".$row['nome']."</td>";
                }
            ?>
            <tr>

            </tr>

        </table>
        
        <footer style="display: flex; justify-content: center;">
            <vr>
            <b><p class="text-center text-muted">©<?php echo date('Y'); ?> <a href="https://www.phsolucoes.site/">PH Soluções</a></p></b>
        </footer>
    </div>
</body>
</html>
