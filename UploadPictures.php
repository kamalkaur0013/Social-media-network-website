<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <?php
        include './ProjectCommon/Header.php';
    ?>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
        
            <div class="container">
            <h1>Upload Pictures</h1>

            <p>Accepted picture types: JPG(JPEG), GIF and PNG.</p>

            <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
            
            <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
            
            <!--<span class='error' style="color:red"><?php echo $error;?></span>-->
            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                
                <div class="row">   
                    <div class="form-group">
                        <!--<div class="col-xs-4">-->
                            <label for="fileUpload">File to Upload:</label>
                            <input type="file" class="form-control" id="fileUpload" name="imageUpload[]" multiple>                            
                        <!--</div>-->
                    </div>
                 </div>
                
                <br /><br/>

                <div class="row">
                    <div class="form-group">
                        <!--<div class="col-xs-4">-->
                            <button type="submit" class="btn btn-primary" value="Submit" name="uploadBtn">Upload</button>
                            &nbsp; &nbsp; &nbsp;<button type="reset" class="btn btn-primary">Reset</button>
                        <!--</div>-->
                    </div>
                </div>  
                
            </form>
        </div>
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>
