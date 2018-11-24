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
    //session_start();
    extract($_POST);
    
  
    //if the submit button is clicked, validate inputs
    if ( isset( $_POST['submit'] ) ) 
    {
        $valid = true;
        //validate input
        if(!isset($userId) || $userId == "")
        {
            $errUserId  = "Enter user ID";
            $valid = false;
        }
        if(!isset($userName) || $userName == "")
        {
            $errUserName  = "Enter user name";
            $valid = false;
        }
        
        $errPhone  = ValidatePhone($phone);
        if($errPhone != "") $valid = false;
        
        if(!isset($password1) || $password1 == "")
        {
            $errPassword1  = "Enter password";
            $valid = false;
        }
        else
        {
            $errPassword1 = ValidatePassword($password1);
            if($errPassword1 != "") $valid = false;
        }
        if(!isset($password2) || $password2 == "")
        {
            $errPassword2  = "Enter password";
            $valid = false;
        }
        else
        {
            $errPassword2 = ValidatePassword2($password1, $password2);
            if($errPassword2 != "") $valid = false;
        }
        
        if($valid)
        {
            //get the sha1-hash of use entered password
            $hashedPassword = sha1($password1);	
                        
            $user1 = new User($userId, $userName, $phone, $hashedPassword);
            $dbConnection = parse_ini_file("./db_connection.ini");
            extract($dbConnection);
            
            try {
                $myPdo = new PDO($dsn, $user, $pw);
                
                $sql = "SELECT * From User Where UserId = \"$userId\"";
                if($myPdo->query($sql)->rowCount() == 0)
                {
                    //add new user
                    $sql = "INSERT INTO User VALUES(:userId, :userName, :phone, :password)";
                    $pStmt = $myPdo->prepare($sql);    
                    $pStmt->execute([  'userId' => $user1->getID(), 
                                       'userName' => $user1->getName(),
                                       'phone' => $user1->getPhone(), 
                                       'password'=>$user1->getPassword()]);
                }
                else
                {
                    $errUserId = "A user with this ID has already signed up";
                }
                $myPdo = null;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            
            if($errUserId =="")
            {
                header("Location: Login.php");
                exit( );
            }
            
        }
        
    }
    
    echo "
    <form class=\"form-horizontal\" method='post' action=\"./NewUser.php\">
        <div class=\"form-group\">
            <div class='col-sm-2'></div>
            <div class='col-sm-4'><h1>Sign Up</h1></div>            
        </div>        
        
        <p>All fields are required.</p> <br>
        
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"userId\">User ID:</label></div>
            <div class='col-sm-4'><input type=\"text\" class=\"form-control\" id=\"userId\" name=\"userId\" value=\"$userId\"></div>
            <p class='col-sm-4 text-danger error'> $errUserId </p>
        </div>
        
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"userName\">Name:</label></div>
            <div class='col-sm-4'><input type=\"text\" class=\"form-control\" id=\"userName\" name=\"userName\" value=\"$userName\"></div>
            <p class='col-sm-4 text-danger error'> $errUserName </p>
        </div>
        
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"phone\">Phone Number:</label><br>(nnn-nnn-nnnn)</div>
            <div class='col-sm-4'><input type=\"text\" class=\"form-control\" id=\"phone\" name=\"phone\" value=\"$phone\"></div>
            <p class='col-sm-4 text-danger error'> $errPhone </p>
        </div>
        
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"password1\">Password:</label></div>
            <div class='col-sm-4'><input type=\"password\" class=\"form-control\" id=\"password1\" name=\"password1\" value=\"$password1\"></div>
            <p class='col-sm-4 text-danger error'> $errPassword1 </p>
        </div>
        
        <div class=\"form-group\">
            <div class='col-sm-2'><label for=\"password2\">Password Again:</label></div>
            <div class='col-sm-4'><input type=\"password\" class=\"form-control\" id=\"password2\" name=\"password2\" value=\"$password2\"></div>
            <p class='col-sm-4 text-danger error'> $errPassword2 </p>
        </div>
        
        <div class='form-group'>
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
            <button type='submit' class='col-sm-12 btn btn-default btn-primary' name='submit'>Submit</button></div>
            <div class='col-sm-2'>
            <button type='reset' class='col-sm-12 btn btn-default btn-primary' name='clear'>Clear</button></div>
        </div>
    </form>
    ";
    ?>
    </div>
<?php include("./ProjectCommon/footer.php"); ?>
</body>

</html>
