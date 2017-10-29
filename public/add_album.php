<?php session_start();?>
<?php require_once('functions.php');?>
<?php require_once('queryfunctions.php');?>
<?php confirm_logged_in();?>
<?php
$title= $year ="";
$errors = array('titleerr' =>'' ,'yearerr' =>'');
$error='';


if (isset($_POST['addAlbum'])){
  $title = check_title_song();
  $year = check_year();
  
  $errpresent = 0;
  foreach ($errors as $key => $value) {
   	if($value!=''){
   		$errpresent = 1;
   	}
  }
?>
<?php require_once('connect_db.php');?>
<?php

	if(!$errpresent){
    	$album_id = insert_album();

    	$target = "uploads/{$_SESSION['user_id']}/album/{$album_id}/art/";
    	$deltarget = $target;
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
                    $c = insert_album_art($target);
                    if ($c) {
                        $_SESSION['album_id'] = $album_id;
                        redirect_to("add_song.php?album_id={$album_id}");
                    }
                }else {
                    $error .= "Sorry, there was a problem uploading your file.";
                }

            }else{
                //delete row and file

                delTree($deltarget);
                delete_album_by_id($album_id);
                $error .= "File of this type not allowed";
            }
        }else{
                $error .= "Please choose an image";
                delTree($deltarget);
                delete_album_by_id($album_id);
        }
	}
}

?>
<?php
$pages = array();
$pages["home.php"] = "Home";
$pages["albums.php"] = "Albums";
$pages["songs.php"] = "Songs";
$pages["add_album.php"] = "Add Album";

$activePage = "add_album.php";
?>


<?php include_once('../layout/header.php');?>
<div id="form">
<form class="form-container col-md-8 col-md-offset-2 text-center" method='POST' action='add_album.php' enctype="multipart/form-data">
	<label><h3>Name:</h3><input class="space" type='text' name='title' value='' placeholder='Title'><?php echo $errors['titleerr'];?></label><br>
	<label><h3>Year:</h3><input class="space" type='text' name='year' value='<?php echo "$year" ?>' placeholder='Year'><?php echo $errors['yearerr'];?></label><br>

    <label> <h3>Album Art:</h3>
            <input class="space" type="hidden" name="size" value="350000">
            <input class="space" type="file" name="photo"> 
    </label>
    <label><?php echo "<br><h4 class='text-center'> $error </h4>";?></label><br>
	<input type='submit' name='addAlbum' class="btn btn-success space" value='Submit'>
    <br>
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

<?php include_once('../layout/footer.php');?>