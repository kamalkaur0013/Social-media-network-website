<?php
include("./ProjectCommon/Header.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Algonquin Social Media</title>
    </head>
    <body>
        <?php
        session_start();
        extract($_POST);
        $user1 = $_SESSION["user"];
       
        //user is not logged in, redirect to login page
        if(!isset($_SESSION["user"]))
        {
            header("Location: Login.php?action=alb");
            exit();
        }
        
        ?>
<?php include("./ProjectCommon/Footer.php"); ?>        
    </body>
</html>