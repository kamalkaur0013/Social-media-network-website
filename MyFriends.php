<?php
include("./ProjectCommon/Header.php");
session_start();

// Displays The accepted friends name and shared albums 
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
            $friendId= $_SESSION['requesterId']; 
        
            echo "<p>Welcome $name ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
                  
             
        ?>
        
       <form method="POST">
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
                <?php
                  
                        
                        
                 // Database conncetion 
                $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                extract($dbConnection);
                $myPdo = new PDO($dsn, $user, $pw);                
                global $myPdo;

                global $arrayFriends; 
                // retreive the function for displaying the accepted friends 
                $arrayFriends = getFriendREQUESTER($ID); 
               
//                foreach ($arrayFriends as $f){
//                    echo $f;
//                }
//                $arrayFriends = getFriendREQUESTER($ID,$friendId); 
                          
                   
                    foreach ($arrayFriends as $row) 
                    {
                         $sqlSelect = "SELECT user.userID as user, user.Name as 'Name', count(album.Album_Id) "
                                . "from user "
                                . "LEFT JOIN album on user.UserId = album.Owner_Id "
                                . "WHERE (album.Accessibility_Code ='Shared' or album.Accessibility_Code is null) "
                                . "And user.UserId = :userID "
                                ;
                        $stmt = $myPdo->prepare($sqlSelect);
                        $stmt->execute(['userID' => $row]);
                        while($row = $stmt->fetch()){
                            $userid = $row['user'];
                            echo "<tr>"; 
                            echo "<td><a href='FriendPictures.php?friendID=$userid'>" . $row['Name'] . "</a></td>";
//                             echo "<td>" . $row['Name'] . "</td>";
                             echo "<td>" . $row['count(album.Album_Id)'] . "</td>";
                              echo "<td> <input type='checkbox' name='selectName[]' value = '$userid'> </td>";
                        } 
                    
                    } // END OF foreach ($arrayFriends as $row)      
                    
                ?>
                          
            </table>
            <input name="Defriend" type = "submit" value = "Defriend Selected" class="btn btn-primary"  onclick="return myFunctionDelete()" />
            <br/>
            <br/>
            <br/>
            
            <?php
                 // When defriend Btn is clicked 
                if(isset($_POST['Defriend']))
                {
                    // If the checkbox is selected for defriend. 
                    if(isset($_POST['selectName']))
                    {
                       $selectN = $_POST['selectName'];

                        //When Defriend Btn clicked will delete friend request sent. 
                        foreach ($selectN as $defriend)
                        {
                          $sqlStatement = "DELETE FROM Friendship "
                                  . "WHERE (Friend_RequesterId = ".$myPdo->quote($defriend)."  AND Friend_RequesteeId= ".$myPdo->quote($ID).") "
                                  . "OR (Friend_RequesterId = ".$myPdo->quote($ID)." AND Friend_RequesteeId = ".$myPdo->quote($defriend)." ) "
                                  . "AND Status = 'accepted'";

                         $stmt = $myPdo->query($sqlStatement);
                         $stmtUpdate= $stmt->fetch(); 
                        }
                    }
                }// END OF if(isset($_POST['Defriend']))
            ?>
            
            <table class=table>
                <tr>
                <p> Friends Request:</p>                                        
                </tr>
                
                <tr>
                    <th>Name</th>  
                    <th>User Id</th>  
                    <th>Accept or Deny</th> 
                </tr>
                
                <tr>
                    
                    <?php
                         // Database conncetion 
                        $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                        extract($dbConnection);
                        $myPdo = new PDO($dsn, $user, $pw);                
                        global $myPdo;
                         
                       $reqsterId=$_SESSION['requesterId']; 
                        $friendId= $_SESSION['requesterId']; 

                      // Will display the name of the requesterId that has sent a request to the requesteeId. 
                      $sqlStatement = "SELECT User.Name, User.UserId
                          FROM Friendship 
                          LEFT JOIN User ON Friendship.Friend_RequesterId  = User.UserId 
                          WHERE (Friend_RequesterId = ".$myPdo->quote($friendId)." || Friend_RequesteeId = ".$myPdo->quote($ID).") 
                          && Status = 'request'";
                        $stmt = $myPdo->query($sqlStatement);
                        $requestF= $stmt->fetchAll(); 
  
                        foreach ($requestF as $row) 
                        {                      
                           echo "<tr>";
                                $Name = $row['Name'];  
                                $usId = $row ['UserId'];
                                echo "<td> $Name </td>";
                                echo "<td> $usId </td>";
                                echo "<td> <input type='checkbox' name='selectR[]' value='$usId'> </td>";
                            echo"</tr>";                 
                        }
                             
                    ?>
                </tr>
                              
            </table>
            <br/>
            <input name="Accept" type = "submit" value = "Accept Selected"  class="btn btn-primary"  />
            <input name="Deny" type = "submit" value = "Deny Selected"  class="btn btn-primary"  onclick="return myFunctionDelete()" />
       </form>
       <?php
      
       
          // When Accept btn is clicked 
            if(isset($_POST['Accept']))
            {
                    
                // if checkbox is selected 
                if(isset($_POST['selectR']))
                {
                    // Database conncetion 
                    $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                    extract($dbConnection);
                    $myPdo = new PDO($dsn, $user, $pw);                
                    global $myPdo;
                    
                    $selectR = $_POST['selectR'];

                    //will update status to accept 
                      foreach ($selectR as $request)
                      {
                        $sql_update = "UPDATE Friendship SET Status= 'accepted' "
                                . "WHERE Friend_RequesterId= ".$myPdo->quote($request)." "
                                . "AND Friend_RequesteeId=".$myPdo->quote($ID)." ";
                                
                         $stmt = $myPdo->query($sql_update);
                         $stmtUpdate= $stmt->fetch(); 
                      }

                }
            } // END OF  if(isset($_POST['Accept']))
            
            // When Deny btn is clicked 
            if(isset($_POST['Deny']))
            {
                  $selectR = $_POST['selectR'];
                    
                // if checkbox is selected 
                if(isset($selectR))
                {
                    // Database conncetion 
                    $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                    extract($dbConnection);
                    $myPdo = new PDO($dsn, $user, $pw);                
                    global $myPdo;
                    
//                    $selectR = $_POST['selectR'];

                    //When deny Btn clicked will delete friend request sent. 
                      foreach ($selectR as $deny)
                      {
                        $sql_Deny= "DELETE FROM Friendship "
                                . "WHERE (Friend_RequesterId = ".$myPdo->quote($deny).""
                                . " AND Friend_RequesteeId= ".$myPdo->quote($ID)." ) "
                                . "AND Status = 'request' ";
                                
                         $stmt = $myPdo->query($sql_Deny);
                         $stmtUpdate= $stmt->fetchAll(); 
                      }
                } // END OF if(isset($_POST['selectR']))
            } // END OF  if(isset($_POST['Deny']))  
       ?>
    </body>
    <script>
        function myFunctionDelete() 
        {
            if( confirm("The selected friends will be defriended!"))
            {
                return true;
            }
            else
            {
                return false; 
            }
           
           
      
        }
        
         
    </script>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>