<?php
    session_start();
    
    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
    }
    if(isset($_POST['nome_lo'])){
        require("../routes/db.php");

        if ($conn->query("INSERT INTO `local`(`nome`) VALUES ('".$_POST['nome_lo']."')") === TRUE) {
            $_SESSION['log'] = "local cadastrado";
            header("Location: ../dashboard/formAdiciona.php");
        } else {
            $_SESSION['log'] = "Error: " . $sql . "<br>" . $conn->error;
            header("Location: ../dashboard/formAdLocal.php");
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
</head>
<body>
    <?php
    if(isset($_SESION['log'])){
        echo $_SESSION['log'];
        unset($_SESSION['log']);
    }
    ?>
    <form action="./formAdLocal.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome_lo" id="nome" required>
        
        <input type="submit" value="Adicionar">
    </form>

    <table>
        <tr>
            <th>Id</th>
            <th>Local</th>
        </tr>


    </table>
</body>
</html>
