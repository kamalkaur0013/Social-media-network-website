<?php
    session_start();

    unset($_SESSION["user"]);
   
    //destroy session
    session_destroy();
    
    header("Location: Index.php");
    exit( );
    ?>
