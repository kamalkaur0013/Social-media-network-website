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
    $accessibilityArray = array();
    //user is not logged in, redirect to login page
    if(!isset($_SESSION["user"]))
    {
        header("Location: Login.php?action=alb");
        exit();
    }
    
    $dbConnection = parse_ini_file("./ProjectCommon/db_connection.ini");
    extract($dbConnection);
    
    try {
        $myPdo = new PDO($dsn, $user, $pw);
         $sql = "SELECT * From Accessibility";
        //make the dropdown list for the accessibility
        foreach($myPdo->query($sql) as $row)
        {
            $accessibility = new Accessibility($row['Accessibility_Code'], $row['Description']);
            $accessibilityArray[] = $accessibility;
        }
    }
    catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    
    if(isset($submitButton))
    {
        //check title is not null
        if(isset($title) && $title != "")
        {
            date_default_timezone_set('America/New_York'); //set time zone
            //$date = date('Y/m/d H:i:s');
            try {
                $sql = "INSERT INTO Album VALUES(:Album_Id, :Title, :Description, :Date_Updated, :Owner_Id, :Accessibility_Code)";
                 $pStmt = $myPdo->prepare($sql);    
                $pStmt->execute([  'Album_Id' => null,
                                   'Title' => $title, 
                                   'Description' => $desc,
                                   'Date_Updated' => date('Y/m/d'),
                                   'Owner_Id' => $user1['UserId'],
                                   'Accessibility_Code' => $accessibilitySelect   ]);
            } catch (Exception $ex) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            header("Location: MyAlbums.php");
            exit();
        }
        else
        {
            $errTitle = "Enter title";
        }
    }
     ?>
    <div class="container">
        </br>
        <?php echo "<p> ".$date."</p>";?>
    <form name = "newAlbumForm" id ="newAlbumForm" class="form-horizontal" method="post" action="./AddAlbum.php" >
    <div class="form-group">
            <div class='col-sm-2'></div>
            <div class='col-sm-4'><h1>Create New Album</h1></div>
    </div>
        
    <p>Welcome <b><?php echo $user1['Name']; ?></b>! (not you? change user <a href='./Login.php?action=out'>here</a>)</p>
    <p class='error'><?php echo $errLogin; ?></p>
    </br>
     
    <div class="form-group">
        <div class='col-sm-2'><label for="title">Title:</label></div>
        <div class='col-sm-4'><input type="text" class="form-control" id="title" name="title" value=""></div>
        <p class='col-sm-4 text-danger error'> <?php echo $errTitle; ?> </p>
    </div>
    <div class="form-group">
        <div class='col-sm-2'><label for="accessibility">Accessibility:</label></div>
        <div class='col-sm-4'>
            <select class ="form-control" name="accessibilitySelect" id="accessibility" >
            <?php  
            foreach($accessibilityArray as $accessibility)
            {
                $a = $accessibility->getAccessibitliyCode();
                $b = $accessibility->getDescription();
              echo "<option value=\"".$accessibility->getAccessibitliyCode()."\">".$accessibility->getDescription()."</option>";
            }
           ?>
           </select>
        </div>
        <p class='col-sm-4 text-danger error'> <?php echo $errAccessibility; ?> </p>
    </div>
    <div class="form-group">
        <div class='col-sm-2'><label for="desc">Description:</label></div>
        <div class='col-sm-4'><textarea type="text" class="form-control" rows="5" id="desc" name="desc" ></textarea></div>
    </div>
    
    <div class='form-group'>
        <div class='col-sm-2'></div>
        <div class='col-sm-2'>
            <button type='submit' class='col-sm-12 btn btn-default btn-primary' name='submitButton'>Submit</button></div>
        <div class='col-sm-2'>
            <button type='reset' class='col-sm-12 btn btn-default btn-primary' name='clearButton'>Clear</button></div>
    </div>
    </form>
   </div>
<?php include("./ProjectCommon/Footer.php"); ?>
</body>
 </html> 