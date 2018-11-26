<?php
include("./ProjectCommon/Header.php");
?>
<html>
    <?php
        include './ProjectCommon/Header.php';        
        include("./ProjectCommon/Class_Lib.php");
        include './ProjectCommon/ConstantsAndSettings.php';
        
        $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
        extract($dbConnection);
        global $myPdo;
        $myPdo = new PDO($dsn, $user, $pw);
        
    ?>
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
        
            
            if (isset($_POST["uploadBtn"]))
            {
                $selectedAlbum = $_POST["albumSelect"];
                $albumTitle = $_POST["albumTitle"];
                $photoDesc = $_POST["photoDesc"];
                $currentDate = date('Y-m-d');
            
                // first make pictures folder if it doesn't exist
                $picFolder = "./Pictures";
                
                if (!file_exists($picFolder))
                {
                    mkdir($picFolder);
                }
                
                // photos will first be put into original pics dir
                $destination = ORIGINAL_PICTURES_DIR;

                if (!file_exists($destination))
                {
                    mkdir($destination);
                }
                
                for ($i = 0; $i < count($_FILES["imageUpload"]["tmp_name"]); $i++)
                {
                    if ($_FILES["imageUpload"]["error"][$i] == 0)
                    {
                        $fileTempPath = $_FILES["imageUpload"]["tmp_name"][$i];
                        $filePath = $destination."/".$_FILES["imageUpload"]["name"][$i];
                        
                        $pathInfo = pathinfo($filePath);
                        $dir = $pathInfo["dirname"];
                        $fileName = $pathInfo["filename"];
                        $ext = $pathInfo["extension"];
                        
                        // add picture info to database
                        $sqlInsertPhoto = "INSERT INTO Picture (Album_Id, FileName, Title, Description,Date_Added) "
                                . "VALUES (:albumId, :fileName, :title, :description, :date)";
                        $stmtInsertPic = $myPdo->prepare($sqlInsertPhoto);
                        $stmtInsertPic->execute(['albumId'=>$selectedAlbum,'fileName'=>$fileName,
                                                'title'=>$albumTitle,'description'=>$photoDesc,'date'=>$currentDate]);
                        
                        // update album date
                        $sqlUpdateDate = "UPDATE Album SET Date_Updated = :updatedDate "
                            . "WHERE Album_Id = :id";
                        $stmtSaveChanges = $myPdo->prepare($sqlUpdateDate);
                        $stmtSaveChanges->execute(['updatedDate'=> $currentDate, 'id'=>$selectedAlbum]);
                        
                        $j = "";

                        while (file_exists($filePath))
                        {
                            $j++;
                            $filePath = $dir."/".$fileName."_".$j.".".$ext;
                        }
                        // move image
                        move_uploaded_file($fileTempPath, $filePath);
                      
                        // create picture object
                        //$picture = new Picture($fileName, $i);
                        
                        // image details
                        $imageDetails = getimagesize($filePath);
                        
                        if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes))
                        {
                            resamplePicture($filePath, ALBUM_PICTURES_DIR, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                            resamplePicture($filePath, ALBUM_THUMBNAILS_DIR, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
                        }
                        else
                        {
                            $error = "Upload files is not a supported type.";
                            unlink($filePath); 
                        }
                    }
                    elseif ($_FILES["imageUpload"]["error"][$i] == 1) 
                    {
                        $error = "$fileName is too large <br>";
                    }
                    elseif ($_FILES["imageUpload"]["error"][$i] == 4)
                    {
                        $error = "No upload file specified <br>";
                    }
                    else
                    {
                        $error = "Error happened while uploading the file(s). Try another time. <br>";
                    }
                }
            }
        
        ?>
        
            <div class="container">
            <h1>Upload Pictures</h1>

            <p>Accepted picture types: JPG(JPEG), GIF and PNG.</p>

            <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
            
            <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
            
            <span class='error' style="color:red"><?php echo $error;?></span>
            
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
                
                <?php
                    $sqlAlbums = "SELECT Album_Id, Title From Album";     
                    $stmtGetAlbum = $myPdo->prepare($sqlAlbums);
                    $stmtGetAlbum->execute();
                    global $myAlbums;
                    $myAlbums = $stmtGetAlbum->fetchAll(); // data array
            
                ?>
                
                <div class="form-group">
                    <label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">Upload to Album:</label>
                    <div class="col-xs-4">                            
                        <select id="selectAlbum" class="form-control" name="albumSelect">    
                            <option value="" disabled selected hidden>Select Album</option>
                            <?php foreach ($myAlbums as $albumChoice): ?>
                                <option value="<?=$albumChoice["Album_Id"]?>" 
                                    <?php if(isset($selectedAlbum) && $selectedAlbum == $albumChoice["Album_Id"]) 
                                        echo 'selected="selected"';?>>
                                        <?=$albumChoice["Title"]?>
                                </option>
                            <?php endforeach ?>

                        </select>                           
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">File to Upload:</label>
                    <div class="col-xs-4">                            
                        <input type="file" class="form-control" id="fileUpload" name="imageUpload[]" multiple>                            
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">Title:</label>
                    <div class="col-xs-4">                            
                        <input type="text" class="form-control" id="title" name="albumTitle">                            
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">Description:</label>
                    <div class="col-xs-4">                            
                        <textarea name="photoDesc" rows="6" cols="48"></textarea>                            
                    </div>
                </div>
               
                <br /><br/>

                    <div class="form-group">
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary" value="Submit" name="uploadBtn">Submit</button>
                            &nbsp; &nbsp; &nbsp;<button type="reset" class="btn btn-primary">Clear</button>
                        </div>
                    </div>
                
                
            </form>
        </div>
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>
