<?php session_start();?>
<?php require_once('functions.php');?>
<?php require_once('queryfunctions.php');?>
<?php confirm_logged_in();?>
<?php require_once('connect_db.php');?>
<?php
$review= $error= '';

if(isset($_POST['addReview'])){

	$review = test_input($_POST['review']);
	$rating = $_POST['rating'];
	$song_id = $_GET['sngid'];
	$reviewer_id = $_SESSION['user_id'];
	if(!empty($review)&&$rating<=10&&$song_id){
			$error .= insert_review();
			redirect_to('view_song.php');
	}else{
			$error.="Looks like an invalid entry was made! Please try again.";
	}

}


?>
<?php 
$activePage = "view_song.php";
include_once('../layout/header.php');
?>
<!-- display song -->
<?php 
//set when calling
if(isset($_GET['sngid']))
	$songs = select_all_songs_or_by_id(false,$_GET['sngid']);
else
	redirect_to('view_song.php');
include_once('single_song.php');

?>
<div class="col-md-8 col-md-offset-2">
<form class="form-container" method='POST' action='add_review.php?sngid=<?php echo "{$songprev['song_id']}" ?>'>
	<textarea class="space" type='text' name='review' value='<?php echo "$review" ?>' placeholder='Write a review...'></textarea>
    <select class="space" name='rating'>
        <?php 
            for($row=1; $row<=10; $row++){
                echo "<option value='{$row}'>{$row}</option>";
            }
        ?>
    </select> 
    <br>
	<input class="btn btn-success space" type='submit' name='addReview' value='Submit'>
	<?php echo $error;?>
</form>
</div>

<?php include_once('../layout/footer.php');?>