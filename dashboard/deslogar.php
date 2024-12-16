<?php
    session_start();

    unset($_SESSION['id']);
    unset($_SESSION['nivel']);
    unset($_SESSION['log']);
    session_destroy();
    header('Location: ../');

?>