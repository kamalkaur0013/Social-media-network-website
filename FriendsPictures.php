<?php
session_start();
include("./ProjectCommon/Header.php");
include("./ProjectCommon/Class_Lib.php");
include ("./ProjectCommon/ConstantsAndSettings.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Algonquin Social Media</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="./ProjectCommon/MyPicturesStyle.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
            
            extract($_POST);
            $user1 = $_SESSION["user"];
            
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
            
            $selectedAlbum = $_POST["albumSelect"];
            $_SESSION["selectedAlbum"] = $selectedAlbum;
            
            // retrieve href ID from my Friends page
            global $getFriendID;
            $getFriendID = $_GET["friendID"];
            echo $getFriendID;
                        
            $userid = $user1["UserId"];

            if (isset($_POST["commentBtn"]))
            {
                $picId = $_POST["commentBtn"];
                $commentText = $_POST["textComment"];
                $sanitizedComment = htmlspecialchars($commentText);   // sanitized comment
                
                if ($sanitizedComment == "")
                {
                    $error = "Please enter a comment!";
                }
                else
                {
                    $sqlInsertComment = "INSERT INTO Comment (Author_Id, Picture_Id, Comment_Text) "
                            . "VALUES (:authorId, :picId, :comment)";
                    $stmtComment = $myPdo->prepare($sqlInsertComment);
                    $stmtComment->execute(['authorId'=>$userid, 'picId'=>$picId, 'comment'=>$sanitizedComment]);
                }
            }
            
        ?>
        
        <div class="container">
            
            <form method="POST">
                <input id="imageID" type="hidden" value="" name="picId">
                <?php
                    $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
                    extract($dbConnection);
                    global $myPdo;
                    $myPdo = new PDO($dsn, $user, $pw);

                    $sql_friendAlbum= "SELECT Album_Id, Title, Date_Updated FROM Album "
                    . "WHERE Owner_Id = :friendID AND Accessibility_Code = 'shared'";
                    
                    $stmtGetFAlbum = $myPdo->prepare($sql_friendAlbum);
                    $stmtGetFAlbum->execute(['friendID' =>$getFriendID]);
                    global $myFAlbums;
                    $myFAlbums = $stmtGetFAlbum->fetchAll(); // data array
                ?>
                
                <div class="form-group">
                    <!--<label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">Upload to Album:</label>-->
                    <div class="col-md-6">                            
                        <select id="selectAlbum" class="form-control" name="albumSelect" 
                                onchange="myFunction(this.form.submit())">    
                            <option value="" disabled selected hidden>Select Album</option>
                            <?php foreach ($myFAlbums as $albumChoice): ?>
                                <option value="<?=$albumChoice["Album_Id"]?>" 
                                    <?php if(isset($selectedAlbum) && $selectedAlbum == $albumChoice["Album_Id"]) 
                                        echo 'selected="selected"';?>>
                                        <?=$albumChoice["Title"]." - updated on ".$albumChoice["Date_Updated"]?>
                                </option>
                            <?php endforeach ?>
                        </select>                           
                    </div>
                </div>
                <br><br>
                <h1 style="margin-left: 200px" id="title"></h1>
                <?php
                $albumSelect = $_SESSION["selectedAlbum"];
                $pics = Picture::getPictures();

                $sqlPictures = "SELECT FileName, Title, Picture_Id, Description FROM Picture "
                                . "WHERE Album_Id = :albumIdSelect";
                $stmtPicture = $myPdo->prepare($sqlPictures);
                $stmtPicture->execute(['albumIdSelect'=>$albumSelect]);
                global $pictureAlbum;
                $pictureAlbum = $stmtPicture->fetchAll();
                
                $sqlComments = "SELECT User.Name, Comment.Picture_Id, Comment.Date, Comment.Comment_Text "
                        . "FROM Comment INNER JOIN User ON User.UserId = Comment.Author_Id"; 
                $stmtComments = $myPdo->prepare($sqlComments);
                $stmtComments->execute();
                global $comments;
                $comments = $stmtComments->fetchAll();             
                
                ?>
                
                 <div class="album" style="position: relative; width: 810px">
                <img id="viewer" src="" alt="" name="albumImage" class="normal">                
                </div>
                <div id="comment-desc-container" style="position: absolute; right: 100px; top: 300px; 
                     overflow-y: scroll; width: 320px; white-space: nowrap;">
                    <p id="description"></p>
                    <p id="commentSection"></p>
                </div>
                <div class="addComments">
                    <span class='error' style="color:red; position: absolute; right: 110px; 
                          bottom: 270px;"><?php echo $error;?></span>
                    <textarea name="textComment" rows="4" cols="40" placeholder="Leave a Comment..." 
                              style="position: absolute; right: 100px; bottom: 150px;"></textarea>
                              
                    <button id="addComment" type="submit" class="btn btn-primary" 
                            name="commentBtn" style="position: absolute; right: 290px; bottom: 100px;">
                        Add Comment</button>
                </div>                
                
                <?php if (count($pictureAlbum)>0): ?>
                <div class="scroll">                
                    <?php foreach ($pics as $upload): ?>
                    <?php foreach ($pictureAlbum as $pic): ?>
                    <?php if ($upload->getName() == $pic["FileName"]): ?>
                    <img id="thumb" class="images" src="<?= $upload->getThumbnailFilePath()?>" 
                         onclick="document.getElementById('viewer').src = '<?= $upload->getAlbumFilePath()?>';
                         document.getElementById('imageID').value = '<?= $pic["Picture_Id"]?>';
                         document.getElementById('title').innerHTML = '<?= $pic["Title"]?>';
                         document.getElementById('description').innerHTML =
                                 '<?php if($pic["Description"] != null) {echo '<b>Description:</b><br>'.$pic["Description"];} ?>';
                         document.getElementById('commentSection').innerHTML = 
                         '<?php 
                             if(count($comments)>0)
                             {                                
                                foreach ($comments as $comment)
                                {
                                    if($pic['Picture_Id'] == $comment['Picture_Id'])
                                    {                                     
                                     echo '<b>'.$comment['Name'].' ('.$comment['Date'].')</b>: '.$comment['Comment_Text'].'<br>';
                                    }                                 
                                }                                 
                             }                              
                         ?>';
                         document.getElementById('addComment').value='<?= $pic["Picture_Id"]?>';">                             
                    <?php endif ?>
                    <?php endforeach ?> 
                    <?php endforeach ?> 
                </div>
                <?php endif ?>
               
               
            </form>
            
        </div>
        
        <script>            
                        
            function myFunction()
            {
                
            }
            
            // have thumbnail clicked to display album size immediately on page load
            window.onload = function() {
                document.getElementById('thumb').click();

            }
        </script>
        
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>