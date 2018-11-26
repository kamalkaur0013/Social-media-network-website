<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <?php
        include './ProjectCommon/Header.php';
        session_start();
         $dbConnection = parse_ini_file("./ProjectCommon/db_connection.ini");
         extract($dbConnection);
         $myPdo = new PDO($dsn, $user, $pw);
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
        
        $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $pw);
        
        // retrieved user session that was created in Login page. This session in an array. 
        $theUser= $_SESSION["user"];
        // getting the UserId from the session, b/c the session is an array. 
        $ID = $theUser['UserId'];
        //getting the Name from the session, b/c the session is an array. 
        $name= $theUser['Name'];
                   
            echo "<p>Welcome $name ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
                   
                            
            // if the Send Friend Request btn is clicked will check for conditions. 
           if(isset($_POST['SendFriendRequest']))
           {
                $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                extract($dbConnection);
                $myPdo = new PDO($dsn, $user, $pw);
                global $myPdo; 

               // A friend request has to follow the following rule:
               // The entered user ID must exist in Database.
                
                $friendId=$_POST['ID']; // The friend ID that is entered by user. 
                // Database conncetion 
                $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                extract($dbConnection);
                $myPdo = new PDO($dsn, $user, $pw);                
                global $myPdo;
                // comapre entered friend ID with UserId's in User table. 
                $sql_select = "SELECT Name FROM User WHERE UserId = ".$myPdo->quote($friendId) ."";
                $stmt = $myPdo->query($sql_select);
                $frindUser=$stmt->fetch(); 
                
                
                // gets the name for the enetered friend ID. 
                $friendName = $frindUser['Name']; 
                      
               // One cannot send a friend request to himself/herself. 
               if($friendId == $ID)
               {
                   $requestSent = "Cannot send Request to yourself $friendId "; 
               }
               // One cannot send a friend request to someone who is already his/her friend.

                //If the request passes the conditions, 
                //the user will get message to confirm that the friend request has sent to the specified user. 
                if ($frindUser !== false)
                { 
                    // Save the name of the friend that has been sent a request. 
                    $_SESSION['frindUser'] = $friendRequestedName; 
                    
                    $requestSent = "Your request has been sent to $friendName (ID: $friendId).Once $friendName accepts your request "
                            . "you and $friendName will be friends and be able to view each other's shared albums.";   
                }
           }
           

        ?>
        <p>Enter the Id of the user you want to be Friends with</p>
        
        <form method = "POST" >
             <td><span class="error" style="color: red"> <?php echo $requestSent; ?></span></td>
             <br>
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
