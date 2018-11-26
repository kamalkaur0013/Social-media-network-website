<?php
include("./ProjectCommon/Header.php");
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Algonquin Social Media</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
        session_start();
        extract($_POST);
       $friendRequestedName= $_SESSION['frindUser']; 
        
        //user is not logged in, redirect to login page
        if(!isset($_SESSION["user"]))
        {
            header("Location: Login.php?action=fri");
            exit();
        }
        ?>
        
       <h1 align='center'>My Friends</h1>
        <?php
        
           $theID= $_SESSION["user"];
            $ID = $theID['UserId'];
            $name= $theID['Name'];
        
            echo "<p>Welcome $name ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
            
        ?>
        
       <form>
           <br/>
            <table class=table> 
            <tr>
                <th>Friends:</th>                                
                <th>        </th>                
                <th><a href='AddFriend.php'>Add Friends</a></th>     
            </tr>
            <tr>              
                <th>Name</th>                                
                <th>Shared Albums</th>                
                <th>Defriend</th>                                 
            </tr>  
            
       </table>
            <input name="Defriend" type = "submit" value = "Defriend Selected" class="btn btn-info"   />
            <br/>
            <br/>
            <br/>
            
            <table class=table>
                <tr>
                <p> Friends Request:</p>                                        
                </tr>
                
                <tr>
                    <th>Name</th>  
                    <th>Accept or Deny</th> 
                </tr>
                <?php
                 echo "<tr>";
                    echo "<th$friendRequestedName </th>";
                 echo "<tr>";
                ?>
            </table>
            <br/>
            <input name="Accept" type = "submit" value = "Accept Selected"  class="btn btn-info"  />
            <input name="Deny" type = "submit" value = "Deny Selected"  class="btn btn-info"  />
       </form>
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>
