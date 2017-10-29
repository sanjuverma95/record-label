<?php

session_start();
require_once('functions.php');
if(!logged_in()&&!isset($_GET['user_id'])){
  redirect_to('index.php');
}

$error = '';

if(isset($_POST['upload'])){
    $target = "uploads/{$_GET['user_id']}/profile_pic/";
    if(!is_dir($target)) {
        mkdir($target,0777,True);
    }
    $target = $target . basename( $_FILES['photo']['name']);
    $pname = basename( $_FILES['photo']['name']);
    $pname = explode('.', $pname);
    if(isset($pname[1])){
        if($pname[1]=='jpg' || $pname[1]=='jpeg' || $pname[1]=='png'){

            // Writes the photo to the server
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $target))
            {
                // Tells you if its all ok
                require_once('connect_db.php');
                require_once('queryfunctions.php');
                // Writes the information to the database
                insert_user_image($target);
                $error .= "The file ". basename( $_FILES['photo']['name']). " has been uploaded, and your information has been added to the directory";
            }else {
                // Gives and error if its not
                $error .= "Sorry, there was a problem uploading your file.";
            }

        }else{
            $error .= "File of this type not allowed";
        }
    }
    else{
        $error .= "Please choose a file";
    }
}

$activePage = "register.php";
?> 
<?php require_once('../layout/header.php');?>
<div id="form">
<form class="form-container col-md-6 col-md-offset-3 text-center" method='POST' action= 'img.php?user_id=<?php echo $_GET['user_id'];?>' enctype="multipart/form-data">
    <label class="space"> <h2>Photo:</h2>
            <input type="hidden" name="size" value="350000">
            <input type="file" name="photo"> 
    </label><br>
    <label><?php echo "<h4 class='text-center'> $error </h4><br>";?></label>     
    <input type='submit' class="btn btn-success space" name='upload' value='Upload'>
    <br>

<?php if(!logged_in()){?>
    <a href='index.php?success=1'>Skip</a>
<?php }?>
</form>
</div>

<style>
    .container{
        margin: 0 auto;
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
        background: none;
    }
    #form{
        display: table-cell;
        vertical-align: middle;
    }
    
    .form-container{
        background-color: rgba(26, 26, 26, 0.75);
    }
</style>
<?php require_once('../layout/footer.php');?>