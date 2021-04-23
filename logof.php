<?php
    session_start();        
    
    include('./class/ConfigClass.php');
    $global->gravaLog($_SESSION['id_usuario'], 2, 2);
    
    session_destroy();
    
    header("Location: login.php");
?>