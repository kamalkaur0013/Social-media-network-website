<?php
include("./ProjectCommon/Header.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Algonquin Social Media</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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