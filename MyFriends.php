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
       <h1 align='center'>My Friends</h1>
        <?php
            echo "<p>Welcome X ! (Not you? Change user <a href='Login.php'>here</a>)</p> ";
        ?>
        
       <form>
           <br/>
            <table class=table> 
            <tr>
                <th>Friends:</th>                                
                <th>        </th>                
                <th><a href='AddFriend.php'>Add Friends</a></th>     
            </tr>
            <tr>              
                <th>Name</th>                                
                <th>Shared Albums</th>                
                <th>Defriend</th>                                 
            </tr>  
       </table>
            <input name="Defriend" type = "submit" value = "Defriend Selected" class="btn btn-info"   />
            <br/>
            <br/>
            <br/>
            
            <table class=table>
                <tr>
                <p> Friends Request:</p>                                        
                </tr>
                
                <tr>
                    <th>Name</th>  
                    <th>Accept or Deny</th> 
                </tr>
            </table>
            <br/>
            <input name="Accept" type = "submit" value = "Accept Selected"  class="btn btn-info"  />
            <input name="Deny" type = "submit" value = "Deny Selected"  class="btn btn-info"  />
       </form>
    </body>
    <?php
        include './ProjectCommon/Footer.php';
    ?>
</html>
