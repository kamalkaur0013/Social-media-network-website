<?php
class User 
{
    private $id;
    private $name;
    private $phone;
    private $password;

    public function __construct($id, $name, $phone, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->password = $password;
    }

    public function getID()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getPassword()
    {
        return $this->password;
    }
}
class Accessibility 
{
    private $accessibilityCode ;
    private $description;
   

    public function __construct($accessibilityCode, $description)
    {
        $this->accessibilityCode = $accessibilityCode;
        $this->description = $description;
    }

    public function getAccessibitliyCode()
    {
        return $this->accessibilityCode;
    }
    public function getDescription()
    {
        return $this->description;
    }
}



function ValidatePhone($phone)
{
    $error = null;
    if(isset($phone) && $phone != "") 
    {
        //regualar expression for phone number
        //$phoneRegex = "/[2-9][0-9][0-9]-[2-9][2-9][2-9]-[0-9][0-9][0-9][0-9]$/";
        $phoneRegex = "/[0-9]{3}-[0-9]{3}-[0-9]{4}/";
        if (!preg_match($phoneRegex, $phone) || strlen($phone) != 12)
        {
            $error = "Incorrect phone number";
        }
    }
    else
    {
        $error = "Phone number cannot be blank";
    }
    return $error;
}

function ValidatePassword($password)
{
    $error = null;
    if(isset($password) && $password != "") 
    {
        //regualar expression for password (at least 1 uppercase, 1 lowercase and 1 digit)
        $passwordRegex = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/";
        if (!preg_match($passwordRegex, $password) || strlen($password) < 6)
        {
            $error = "Incorrect password";
        }
    }
    else
    {
        $error = "Password cannot be blank";
    }
    return $error;
}

function ValidatePassword2($password1, $password2)
{
    $error = null;
    if(isset($password1) && $password1 != "" && isset($password2) && $password2 != "") 
    {
        if (strcmp($password1, $password2) != 0)
        {
            $error = "Password is not matched.";
        }
    }
    
    return $error;
}


?>