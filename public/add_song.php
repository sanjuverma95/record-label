<?php session_start();?>
<?php require_once('functions.php');?>
<?php require_once('queryfunctions.php');?>
<?php confirm_logged_in();?>
<?php require_once('connect_db.php');?>
<?php
if(!isset($_GET['album_id'])){
	redirect_to("albums.php?select=1&uid={$_SESSION['user_id']}");
}
$album_id=$_GET['album_id'];//Add song right after creation create another variable $album_id set it to id of album to which song has to be added

$title= $genre_id ="";
$errors = array('titleerr' =>'');

$error = '';

if (isset($_POST['addSong'])){
  $title = check_title_song();
  $genre_id = $_POST['genre'];
  $errpresent = 0;
  foreach ($errors as $key => $value) {
   	if($value!=''){
   		$errpresent = 1;
   	}
  }
?>
<?php

	if(!$errpresent){
		if(isset($_GET['esngid'])&&isset($_GET['edit'])&&$_GET['edit']==1){
			update_song($_GET['esngid']);
			redirect_to('my_songs.php');
		}
    	$song_id = insert_song();

    	$target = "uploads/{$_SESSION['user_id']}/album/{$album_id}/";
    	$deltarget = $target;
		if(!is_dir($target)) {
 	   		mkdir($target,0777,True);
		}

		$target = $target . basename( $_FILES['song']['name']);
		$pname = basename( $_FILES['song']['name']);
		$pname = explode('.', $pname);
        if(isset($pname[1])){
            if($pname[1]=='mp3' || $pname[1]=='flac' || $pname[1]=='ogg'){

                // Writes the song to the server
                if(move_uploaded_file($_FILES['song']['tmp_name'], $target))
                {
                    $c = insert_song_loc($target);
                    if ($c) {
                        $_SESSION['song_id'] = $song_id;
                        redirect_to("add_song.php?album_id={$album_id}");
                    }
                }else {
                    $error .= "Sorry, there was a problem uploading your file.";
                    delTree($deltarget);
                    delete_song_by_id($song_id);
                    $error .= "File of this type not allowed";
                }

            }else{
                //delete row and file

                delTree($deltarget);
                delete_song_by_id($song_id);
                $error .= "File of this type not allowed";
            }
        }else{
            $error .= "Please select a song";
            delTree($deltarget);
            delete_song_by_id($song_id);
        }
	}
}

?>
<?php
$activePage = "add_song.php";
?>


<?php include_once('../layout/header.php');?>
<div id="form">
<form class="form-container col-md-8 col-md-offset-2" method='POST' action='<?php
		if(isset($_GET['esngid'])&&isset($_GET['edit'])&&$_GET['edit']==1){ 
			echo "add_song.php?esngid={$_GET['esngid']}&edit=1&album_id={$_GET['album_id']}";
		}else{
			echo "add_song.php?album_id={$_GET['album_id']}";
		}?>' 
	enctype="multipart/form-data">
	<label>
        <h3>Title:</h3>
        <input class="space" type='text' name='title' value='' placeholder='Title'><br><?php echo $errors['titleerr'];?>
    </label><br>
	<label>
        <h3>Genre:</h3>
		<select name='genre' >
			<?php 
				$gquery = "SELECT * FROM GENRE ";
				$rows = mysqli_query($conn, $gquery);
				while($row=mysqli_fetch_assoc($rows)){
					echo "<option class='genre{$row['g_id']}' value='{$row['g_id']}' style='padding:5px;'>{$row['name']}</option>";
				}
			?>
		</select> 
	</label><br>
<?php

		if(!isset($_GET['esngid'])||!isset($_GET['edit'])||!$_GET['edit']==1){
?>
	<label> <h3>Song:</h3>
            <input type="hidden" name="size" value="5000000">
            <input type="file" name="song">
    </label><br> 
    <label><?php echo "<br><h4 class='text-center'> $error </h4>";?></label>
<?php
}
?>
	<input class="btn btn-success space" type='submit' name='addSong' value='Submit'>
    <br>
</form>
</div>

<?php include_once('../layout/footer.php');?>