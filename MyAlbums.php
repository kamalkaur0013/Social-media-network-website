<?php
include("./ProjectCommon/Header.php");
include("./ProjectCommon/Class_Lib.php");
?>
<html>
<head>
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
            $user1 = $_SESSION["user"];
            
            // db connection
            $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
            extract($dbConnection);
            global $myPdo;
            $myPdo = new PDO($dsn, $user, $pw);

            //user is not logged in, redirect to login page
            if(!isset($_SESSION["user"]))
            {
                header("Location: Login.php?action=alb");
                exit();
            }
            // get user's name from array session
            $name = $user1["Name"];
            $id = $user1["UserId"];
            // drop down selection
            $accessSelection = $_POST["accessChoice"];
            
             //save changes for accessibility
            if (isset($_POST["saveBtn"]))
            {  
                $albumIdArray = array(); // album id array
                $accessCode = array();   // accessibility code array
                foreach ($accessSelection as $value) 
                {
                    $accessSelection_explode = explode("|", $value);  // explode to remove delimiter
                    
                    array_push($albumIdArray, $accessSelection_explode[0]); 
                    array_push($accessCode, $accessSelection_explode[1]);
                }
                
                // combine albumId and accessCode arrays into one array of key value pairs
                $combinedArray = array_combine($albumIdArray, $accessCode);
                
                foreach ($combinedArray as $albumId => $accessibilityCode) 
                {
                    $sqlUpdateAccess = "UPDATE Album SET Accessibility_Code = :updatedAccess "
                            . "WHERE Album_Id = :id AND Owner_Id = :userId";
                    $stmtSaveChanges = $myPdo->prepare($sqlUpdateAccess);
                    $stmtSaveChanges->execute(['updatedAccess'=> $accessibilityCode, 'id'=>$albumId, 'userId'=>$id]);
                }
        
            }
        
        ?>
        
        <form method="POST">

        <div class="container">

        <h1>My Albums</h1>

        <p>Welcome <?php echo $name ?>! (Not you? Change user <a href='Logout.php'>here</a>)</p>

        <br>

        <a href="AddAlbum.php" style="float: right; margin-right: 100px">Create a New Album</a>

        <table class="table">
            <tr>
                <th>Title</th>
                <th>Date Updated</th>
                <th>Number of Pictures</th>
                <th>Accessibility</th>
                <th></th>
            </tr>

            <?php 

            // get user ID
            $user_id = $user1["UserId"];
            // get albums
            $sqlGetAlbums = "SELECT Album.Album_Id, Album.Title, Album.Date_Updated, COUNT(Picture.Picture_Id) as 'Total', Album.Accessibility_Code "
                    . "FROM Album "
                    . "LEFT JOIN Picture ON (Picture.Album_Id=Album.Album_Id) "
                    . "WHERE Album.Owner_Id = :userId "
                    . "Group BY Album.Album_Id";

            $stmtAlbum = $myPdo->prepare($sqlGetAlbums);
            $stmtAlbum->execute(["userId"=>$user_id]);
            global $albumArray;
            $albumArray = $stmtAlbum->fetchAll(); 

            // get accessibility description for dropdown

            $sqlAccessbility = "SELECT Accessibility.Description, Accessibility.Accessibility_Code FROM Accessibility";
            $stmtAccess = $myPdo->prepare($sqlAccessbility);
            $stmtAccess->execute();
            global $accessArray;
            $accessArray = $stmtAccess->fetchAll();

            ?>                
            <!--display each album-->
            <?php foreach ($albumArray as $album): ?>
            <tr>
                <td><a href="MyPictures.php?album_id=<?php echo $album['Album_Id']?>"><?=$album["Title"]?></a></td>
                <td><?=$album["Date_Updated"]?></td>
                <td><?=$album["Total"]?></td>
                <td>
                    <select class="form-control" name="accessChoice[]">  
                     <!--set drop down values to accessibility codes-->
                     <!--set drop down selection to the albums accessibility code-->
                    <?php foreach ($accessArray as $accessOption): ?>
                        <option value="<?=$album['Album_Id']?>|<?=$accessOption['Accessibility_Code']?>"
                            <?php if($album['Accessibility_Code'] == $accessOption['Accessibility_Code']) 
                                echo 'selected="selected"';?>>
                            <!--show accessibility description as drop down option-->
                            <?=$accessOption["Description"]?>
                        </option>
                    <?php endforeach ?>

                    </select>
                </td>
                <!--album delete redirects to delete.php and back to myalbums-->
                <td><a href="Delete.php?album_id=<?php echo $album['Album_Id']?>" value="<?=$album['Album_Id']?>" 
                       name="deleteAlbum" onclick="return deleteAlbum('<?php echo $album['Title']?>')">delete</a></td>
            </tr>
            <?php endforeach ?>                

        </table>

        <button type="submit" class="btn btn-primary" value="saveChanges" 
                name="saveBtn" style="float: right; margin-right: 100px">Save Changes</button>

        </div>

    </form>

        <script>
            
            function deleteAlbum(albumTitle) 
            {
                if (confirm("The " + albumTitle + " album will be deleted including all its pictures!"))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            
        </script>   
        
<?php include("./ProjectCommon/Footer.php"); ?>        
    </body>
</html>
