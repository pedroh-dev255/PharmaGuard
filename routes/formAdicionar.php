<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
}

require_once("./db.php");
    $result_list = $conn->query("SELECT * FROM local");

    if(isset($_POST['nome']) && isset($_POST['principio'])){
        require_once("../routes/db.php");
        if($_POST['quant']>1){
            $i=0;
            for($i=1;$i<=$_POST['quant'];$i++){
                if ($conn->query("INSERT INTO `medicamento`(`nome`, `principio`, `validade`, `adicionado`, `id_arm`, `id_user`) VALUES ('".$_POST['nome']."', '".$_POST['principio']."', '".$_POST['data-val']."', '".date("Y-m-d")."', ".$_POST['arm'].", ".$_SESSION['id'] .")") === TRUE) {
                    $_SESSION['log'] = "Medicamento cadastrado";
                    $_SESSION['log1'] = "success"; // success , warning, error
                    header("Location: ../dashboard/formAdiciona.php");
                    exit();
                } else {
                    $_SESSION['log'] = "Error: " . $sql . "<br>" . $conn->error;
                    $_SESSION['error'] = "success"; // success , warning, error
                    header("Location: ../dashboard/formAdiciona.php");
                    exit();
                }
            }
        }else if($_POST['quant']=1){
            if ($conn->query("INSERT INTO `medicamento`(`nome`, `principio`, `validade`, `adicionado`, `id_arm`, `id_user`) VALUES ('".$_POST['nome']."', '".$_POST['principio']."', '".$_POST['data-val']."', '".date("Y-m-d")."', ".$_POST['arm'].", ".$_SESSION['id'] .")") === TRUE) {
                $_SESSION['log'] = "Medicamento cadastrado";
                $_SESSION['log1'] = "success"; // success , warning, error
                header("Location: ../dashboard/formAdiciona.php");
                exit();
            } else {
                $_SESSION['log'] = "Error: " . $sql . "<br>" . $conn->error;
                $_SESSION['log1'] = "error"; // success , warning, error
                header("Location: ../dashboard/formAdiciona.php");
                exit();
            }
        }

    }else{
        $_SESSION['log']="Post invalido";
        $_SESSION['log1'] = "error"; // success , warning, error
        header("Location: ../dashboard/formAdiciona.php");
        exit();
    }

    $conn->close();
    $conn_press->close();


    ?>