<?php
    session_start();

    if(isset($_SESSION['id'])){
        header('Location: ../dashboard/');
    }

    require("db.php");
    $conn->close();


    if(isset($_POST['login']) && isset($_POST['pass'])){
        // Carrega conexão com banco de dados
        
        // Prepara a consulta SQL para evitar SQL Injection
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $conn_press->prepare($sql);
        $stmt->bind_param('s', $_POST['login']);
        $stmt->execute();
        $result = $stmt->get_result();


        // Verifica se o usuário foi encontrado
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifica a senha usando password_verify (senha é hash no banco de dados)
            if (password_verify($_POST['pass'], $user['passwordHash'])) {
                // Salva os dados do usuário na sessão
                $_SESSION['id'] = $user['id'];
                //nivel de acesso   
                $_SESSION['nivel'] = $user['profile'];
                    
                // Redireciona para o dashboard
                header("Location: ../dashboard/");
                $_SESSION['log'] = "Bem vindo ". ucfirst(trim(strtolower($user['name'])));
                $_SESSION['log1'] = "success"; // success , warning, error

                exit();
            } else {
                header("Location: ../");
                $_SESSION['log'] = "Senha incorreta";
                $_SESSION['log1'] = "error"; // success , warning, error
                exit();
            }
        } else {
            $_SESSION['log'] = "Usuário não encontrado";
            $_SESSION['log1'] = "error"; // success , warning, error
            header("Location: ../");
            exit();
        }
        $conn_press->close();
        
    }else{
        header("Location: ../");
        $_SESSION['log'] = "Post Invalido";
        $_SESSION['log1'] = "error"; // success , warning, error
        exit();
    }


?>