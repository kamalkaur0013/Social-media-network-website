<?php
include_once "ConstantsAndSettings.php";

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


class Picture {
    private $fileName;
    private $id;
    
    public static function getPictures() 
    {
        $pictures = array();
        $files = scandir(ALBUM_THUMBNAILS_DIR);
        $numFiles = count($files);
        
        if ($numFiles > 2)
        {
            for ($i = 2; $i < $numFiles; $i++)
            {
                $ind = strrpos($files[$i], "/");
                $fileName = substr($files[$i], $ind);
                $picture = new Picture($fileName, $i);
                $pictures["$i"] = $picture;
            }
        }
        return $pictures;
    }
    
    public function __construct($fileName,$id) {
         $this->fileName = $fileName;
         $this->id = $id;
     }
     
     public function getId() {
         return $this->id;
     }
     
       public function getName() {
         $ind = strrpos($this->fileName, ".");
         $name = substr($this->fileName, 0, $ind);
         return $name;
     }
    
    public function getAlbumFilePath() {
        return ALBUM_PICTURES_DIR."/".$this->fileName;
    }
    
    public function getThumbnailFilePath() {
        return ALBUM_THUMBNAILS_DIR."/".$this->fileName;
    }
    
    public function getOriginalFilePath() {
        return ORIGINAL_PICTURES_DIR."/".$this->fileName;
    }
    
}

function resamplePicture($filePath, $destinationPath, $maxWidth, $maxHeight)
{
    if (!file_exists($destinationPath))
    {
            mkdir($destinationPath);
    }

    $imageDetails = getimagesize($filePath);

    $originalResource = null;
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
            $originalResource = imagecreatefromjpeg($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
            $originalResource = imagecreatefrompng($filePath);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
            $originalResource = imagecreatefromgif($filePath);
    }
    $widthRatio = $imageDetails[0] / $maxWidth;
    $heightRatio = $imageDetails[1] / $maxHeight;
    $ratio = max($widthRatio, $heightRatio);

    $newWidth = $imageDetails[0] / $ratio;
    $newHeight = $imageDetails[1] / $ratio;

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    $success = imagecopyresampled($newImage, $originalResource, 0, 0, 0, 0, $newWidth, $newHeight, $imageDetails[0], $imageDetails[1]);

    if (!$success)
    {
            imagedestroy(newImage);
            imagedestroy(originalResource);
            return "";
    }
    $pathInfo = pathinfo($filePath);
    $newFilePath = $destinationPath."/".$pathInfo['filename'];
    if ($imageDetails[2] == IMAGETYPE_JPEG) 
    {
            $newFilePath .= ".jpg";
            $success = imagejpeg($newImage, $newFilePath, 100);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_PNG) 
    {
            $newFilePath .= ".png";
            $success = imagepng($newImage, $newFilePath, 0);
    } 
    elseif ($imageDetails[2] == IMAGETYPE_GIF) 
    {
            $newFilePath .= ".gif";
            $success = imagegif($newImage, $newFilePath);
    }

    imagedestroy($newImage);
    imagedestroy($originalResource);

    if (!$success)
    {
            return "";
    }
    else
    {
            return $newFilePath;
    }
}

?>
