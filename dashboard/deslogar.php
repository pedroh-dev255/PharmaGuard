<?php
    session_start();

    unset($_SESSION['id']);
    unset($_SESSION['log']);
    header('Location: ../');

?>