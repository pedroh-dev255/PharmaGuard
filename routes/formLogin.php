<?php
    session_start();

    if(isset($_SESSION['id'])){
        header('Location: ../dashboard/');
    }

    if(isset($_POST['senha']) && isset($_POST['user']) && $_POST['user'] != "" || $_POST['user'] != null && $_POST['senha'] != "" || $_POST['senha'] != null){
        $email=$_POST['user'];
        $senha=$_POST['senha'];

        unset($_POST['user']);
        unset($_POST['senha']);

        require_once("db.php");

        $sql = "SELECT * FROM usuarios where usuario = '".$email."' && senha = '".$senha."'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION['id'] = $row['id'];
                header('Location: ../dashboard/');
            }
        } else {
            $_SESSION['log'] = "Nome ou senha incorretos";
            header('Location: ../');
        }
        $conn->close();

    }else{
        
        $_SESSION['log'] = "Post invalido";
        header('Location: ../');
    }


?>