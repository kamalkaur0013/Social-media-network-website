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
            header("Location: Login.php?action=fri");
            exit();
        }
        ?>
        
       <h1 align='center'>My Friends</h1>
        <?php
            echo "<p>Welcome X ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
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
