<?php
// deleting albums and their pictures

    $dbConnection = parse_ini_file("ProjectCommon/db_connection.ini");
    extract($dbConnection);
    global $myPdo;
    $myPdo = new PDO($dsn, $user, $pw);

    // retrieve href album ID
    $albumIdDelete = $_GET["album_id"];
    
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

?>

