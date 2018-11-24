<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <?php
        include './ProjectCommon/Header.php';
    ?>
    <head>
        <meta charset="UTF-8">
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Add Friend</title>
    </head>
    <body>
        <h1 align='center'>Add Friend</h1>
        <?php
            echo "<p>Welcome X ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
        ?>
        <p>Enter the Id of the user you want to be Friends with</p>
        
        <form method = "POST" >
            <tr>
                 <td>ID: </td><td><input type = "text" name = "ID" ></td>
            </tr>
            
            <input name="SendFriendRequest" type = "submit" value = "Send Friend Request"  class="btn btn-info" />
        </form>
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>
