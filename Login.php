<?php
include("./ProjectCommon/Header.php");
include("./ProjectCommon/Class_Lib.php");
?>
<html>
<head>
    <title>Algonquin Social Media</title>
    
</head>
<body>
    <div class="container">
        <br/>
        
    <?php
    session_start();
    extract($_POST);
    //if the user is already logged in, then destroy session firstly.
    if($_REQUEST['action']=='out')
    {
        unset($_SESSION["user"]);

        //destroy session
        session_destroy();
    }
    //save the previous page 
    if($_REQUEST['action']=='alb')
    {
       $redirect = 'alb';
    }
    else if($_REQUEST['action']=='fri')
    {
        $redirect = 'fri';
    }
    else if($_REQUEST['action']=='pic')
    {
        $redirect = 'pic';
    }
    else if($_REQUEST['action']=='up')
    {
        $redirect = 'up';
    }
   
    // submit button is clicked
    if ( isset( $_POST['submit'] ) ) 
    {
        //validate
        $valid = true;
        if(!isset($userId) || $userId == "")
        {
            $errUserId  = "Enter user ID";
            $valid = false;
        }
        if(!isset($password) || $password == "")
        {
            $errPassword  = "Enter password";
            $valid = false;
        }
        
        if($valid)
        {
            $dbConnection = parse_ini_file("./db_connection.ini");
            extract($dbConnection);
            try {
                $myPdo = new PDO($dsn, $user, $pw);
                $sql = "SELECT * From User  Where UserId = :userId AND Password = :password";

                $hashedPassword = sha1($password);	

                $pStmt = $myPdo->prepare($sql);    
                $pStmt->execute([  'userId' => $userId, 
                                   'password' => $hashedPassword ]);

                if($pStmt->rowCount() == 0)
                {
                    $errLogin = "Incorrect user ID and/or Password!";
                }
                else
                {
                    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION["user"] = $row;
                    
                    //redirect to the previous page user tried to move
                    if (!strcmp($redirect,'alb'))
                    {
                        header("Location: MyAlbums.php");
                        exit( );
                    }
                    else if (!strcmp($redirect,'fri'))
                    {
                        header("Location: MyFriends.php");
                        exit( );
                    }
                    else if(!strcmp($redirect,'pic'))
                    {
                        header("Location: MyPictures.php");
                        exit( );
                    }
                    else if(!strcmp($redirect,'up'))
                    {
                        header("Location: UploadPictures.php");
                        exit( );
                    }
                    else
                    {
                        header("Location: MyPictures.php");
                        exit( );
                    }
                }
                $myPdo = null;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
    //add action value to remember the previous page
    if(isset($redirect))
    {
        if(!strcmp($redirect, 'alb'))
        {
           echo "<form class=\"form-horizontal\" method='post' action=\"./Login.php?action=alb\">"; 
        }
        else if(!strcmp($redirect, 'fri'))
        {
            echo "<form class=\"form-horizontal\" method='post' action=\"./Login.php?action=fri\">"; 
        }
        else if(!strcmp($redirect, 'pic'))
        {
            echo "<form class=\"form-horizontal\" method='post' action=\"./Login.php?action=pic\">"; 
        }
        else if(!strcmp($redirect, 'up'))
        {
            echo "<form class=\"form-horizontal\" method='post' action=\"./Login.php?action=up\">"; 
        }
        
    }
    else
    {
        echo "<form class=\"form-horizontal\" method='post' action=\"./Login.php\">"; 
    }
    
    echo "
        <div class=\"form-group\">
            <div class='col-sm-2'></div>
            <div class='col-sm-4'><h1>Log In</h1></div>
            
        </div>
        
        <p>You need to <a href='./NewUser.php'>sign up</a> if you are a new user</p> <br>
        <p class='error'>$errLogin</p>
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"userId\">User ID:</label></div>
            <div class='col-sm-4'><input type=\"text\" class=\"form-control\" id=\"userId\" name=\"userId\" value=\"$userId\"></div>
            <p class='col-sm-4 text-danger error'> $errUserId </p>
        </div>
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"password\">Password:</label></div>
            <div class='col-sm-4'><input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" value=\"$password\"></div>
            <p class='col-sm-4 text-danger error'> $errPassword </p>
        </div>
        <div class='form-group'>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
            <button type='submit' class='btn btn-default btn-primary' name='submit'>Submit</button>&nbsp
            <button type='reset' class='btn btn-default btn-primary' name='clear'>Clear</button></div>
        </div>
    </form>
    ";
    ?>
        </div>
<?php include("./ProjectCommon/Footer.php"); ?>
</body>

</html>