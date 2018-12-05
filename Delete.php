<?php
// deleting albums and their pictures

    $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
    extract($dbConnection);
    global $myPdo;
    $myPdo = new PDO($dsn, $user, $pw);

    // retrieve href album ID
    $albumIdDelete = $_GET["album_id"];
    
    if ($albumIdDelete != null) 
    {
        // first delete pictures in the selected album
        $sqlDeletePics = "DELETE FROM Picture WHERE Album_Id = :albumId";    
        $stmtDeletePics = $myPdo->prepare($sqlDeletePics);
        $stmtDeletePics->execute(['albumId'=>$albumIdDelete]);

        // then delete the album
        $sqlDeleteAlbum = "DELETE FROM Album WHERE Album_Id = :albumId";
        $stmtDeleteAlbum = $myPdo->prepare($sqlDeleteAlbum);
        $stmtDeleteAlbum->execute(['albumId'=>$albumIdDelete]);

        // redirect to my albums
        header("Location: MyAlbums.php");
        exit;
    }
    
    // get href url id for file path
    $fileToDeleteThumb = $_GET['file_path'];
    
    if ($fileToDeleteThumb != null)
    {
        $pathInfo = pathinfo($fileToDeleteThumb);
        $ext = $pathInfo["extension"];
        $fileName = $pathInfo["filename"];
        $path = $fileName.".".$ext;

        $albumfolder = "Pictures/AlbumPictures/";
        $originalFolder = "Pictures/OriginalPictures/";

        $fileToDeleteAlbum = $albumfolder.$path;
        $fileToDeleteOriginal = $originalFolder.$path;
        //echo $fileToDeleteAlbum;

        unlink($fileToDeleteThumb);
        unlink($fileToDeleteAlbum);
        unlink($fileToDeleteOriginal);

        // redirect to my pictures
        header("Location: MyPictures.php");
        exit;
    }


?>

