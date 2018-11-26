<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        echo '<footer style="position: absolute; bottom: 0;';
        echo '      width: 100%; height: 60px; background-color: darkgreen;">';
        echo '    	<div class="container">';
        echo '            <p style="text-align: center; padding: 10px; color: white;">';
        echo '    &copy; Algonquin College 2010 –';
        date_default_timezone_set("America/Toronto"); print Date("Y"); 
        echo '.    All Rights Reserved';
        echo '         </p>';
        echo '    	</div>';
        echo ' </footer>';
       
        echo '<script src="/AlgCommon/Scripts/jquery-2.2.4.min.js" type="text/javascript"></script>';
        echo '<script src="/AlgCommon/Contents/js/bootstrap.min.js" type="text/javascript"></script>';

        ?>
    </body>
</html>
