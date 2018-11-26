<?php
include("./ProjectCommon/Header.php");
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
            $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
            extract($dbConnection);
            global $myPdo;
            $myPdo = new PDO($dsn, $user, $pw);
            
            $selectedAlbum = $_POST["albumSelect"];
            
            // retrieve href album ID from my albums page
            $albumIdDisplay = $_GET["album_id"];
        ?>
        
        <div class="container">
            
            <form method="POST">
                
                <h1 style="text-align: center">My Pictures</h1>
                
                <?php
                    $sqlAlbums = "SELECT Album_Id, Title, Date_Updated From Album";     
                    $stmtGetAlbum = $myPdo->prepare($sqlAlbums);
                    $stmtGetAlbum->execute();
                    global $myAlbums;
                    $myAlbums = $stmtGetAlbum->fetchAll(); // data array
            
                ?>
                
                <div class="form-group">
                    <!--<label for="fileUpload" class="col-xs-2 control-label" style="text-align: left;">Upload to Album:</label>-->
                    <div class="col-md-6">                            
                        <select id="selectAlbum" class="form-control" name="albumSelect">    
                            <option value="" disabled selected hidden>Select Album</option>
                            <?php foreach ($myAlbums as $albumChoice): ?>
                                <option value="<?=$albumChoice["Album_Id"]?>" 
                                    <?php if($albumChoice["Album_Id"] == $albumIdDisplay) 
                                        echo 'selected="selected"';?>>
                                        <?=$albumChoice["Title"]." - updated on ".$albumChoice["Date_Updated"]?>
                                </option>
                            <?php endforeach ?>

                        </select>                           
                    </div>
                </div>
                
            </form>
            
        </div>
        
    </body>
</html>
