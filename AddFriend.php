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
         $friendName = $frindUser['Name']; 
                $pass = true;
        // retrieved user session that was created in Login page. This session in an array. 
        $theUser= $_SESSION["user"];
        // getting the UserId from the session, b/c the session is an array. 
        $ID = $theUser['UserId'];
        //getting the Name from the session, b/c the session is an array. 
        $name= $theUser['Name'];
        
        function getFriendREQUESTER($userID) 
{
        global $myPdo;
        $friends = array();
        $sqlSelectFriends = 'SELECT * FROM Friendship '
                . 'WHERE (Friend_RequesterId = :userId OR Friend_RequesteeId = :userId) '
                . 'AND Status = "accepted"';
        $stmt = $myPdo->prepare($sqlSelectFriends);
        $stmt->execute(['userId' => $userID]);
        foreach ($stmt as $row) { //puts all friends into an array
            $friends[] = $row['Friend_RequesterId'];
            $friends[] = $row['Friend_RequesteeId'];

        }
        //remove the username from the array since they are not friends with themselves 
       foreach (array_keys($friends, $userID) as $key) {
        unset($friends[$key]);
    }
    return $friends;
}
        
        
        
        function validateFriend($ID,$friendId){
            global $myPdo;
            

                 if($friendId == $ID)
                 {
                  $requestSent = "You Cannot send Request to yourself $friendId ";
                    return $requestSent;
                    //$pass = false;
                } 
                    
                    $friends = getFriendREQUESTER($ID);
                    
                    if (in_array($friendId, $friends))
                    {
                        return "Your already friends ";
                    }
                    
                    
                    $sql_select = "SELECT * FROM Friendship "  ///Requested to be friends 
                    . "WHERE (Friend_RequesterId = :requesterID AND Friend_RequesteeId = :RequestedID AND (Status = 'request' "
                            . "OR Status = 'accepted'))";

                    $stmt = $myPdo->prepare($sql_select);
                    $stmt->execute(['requesterID' => $ID, 'RequestedID' => $friendId]);
                    $pendingFriendship = $stmt->fetch();
                    
                    if($pendingFriendship != null)
                    {
                        return "You have already sent this person request or are already freinds!";

                    }
                    
                     $sqlPendingFriends = "SELECT * FROM Friendship "
                        . "WHERE (Friend_RequesterId = :requesterID AND Friend_RequesteeId = :RequestedID "
                             . "AND Status = 'request'   )";
                    $stmt = $myPdo->prepare($sqlPendingFriends);
                    $stmt->execute(['requesterID' => $friendId, 'RequestedID' => $ID]);
                    $pendingFriendship = $stmt->fetch();
                if ($pendingFriendship != null)
                {
                    $sqlStatement = 'UPDATE Friendship SET Status = "accepted"'
                            . ' WHERE (Friend_RequesterId = :userId1 && Friend_RequesteeId = :userId2) '
                            . 'OR (Friend_RequesterId = :userId2 && Friend_RequesteeId = :userId1)';
                    $pStmt = $myPdo->prepare($sqlStatement);
                    $pStmt->execute(['userId1' => $ID, 'userId2' => $friendId]);

                    return "you are friends now, b/c both parties sent a request!";
                }
//                  // FriendShip table have to insert the requeterID ($ID) and RequsteeID ($friendId), status = 'request'
                        $sql_insert = "INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status)
                                VALUES (?,?, 'request')";
                        $stmt= $myPdo->prepare($sql_insert);
                        $stmt->execute([$ID, $friendId,]);


                        $requestSent = "Your request has been sent to $friendName (ID: $friendId).Once $friendName accepts your request "
                                . "you and $friendName will be friends and be able to view each other's shared albums."; 
                        return $requestSent; 
   
      
    } // END OF function validateFriend($ID,$friendId)
           
            echo "<p>Welcome $name ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
                   
                            
            // if the Send Friend Request btn is clicked will check for conditions. 
           if(isset($_POST['SendFriendRequest']))
           {
                $friendId=$_POST['ID'];
                global $myPdo; 
                if($_POST['ID'] != "")
                {
                     
                    $requestSent = validateFriend($ID, $friendId);
                    echo 'Test';
                }
                else{
                    $requestSent = "Please enter a Friend ID !";
                }

                     // $ID which as a user is now the Requester
                        $_SESSION['requesterId']= $ID; 
   
              
           } // END OF  if(isset($_POST['SendFriendRequest']))
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