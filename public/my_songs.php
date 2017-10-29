<?php 
session_start();
require_once('functions.php');
confirm_logged_in();
?>
<?php require_once('queryfunctions.php');?>
<?php require_once('connect_db.php');?>
<?php 
$activePage = "my_songs.php";
include_once('../layout/header.php');
?>
<?php

$songs = select_all_songs_or_by_id($_SESSION['user_id']);

$flag=0;
while($song = mysqli_fetch_assoc($songs)){
	// $a = find_album_by_id($song['album_id']);
    $flag=1;
	?>
	<br>
	<div class="song genre<?php echo $song['g_id'];?>">
		<a href='add_review.php?sngid=<?php echo "{$song['song_id']}" ?>'>
		  <img class="song-art" src='<?php echo $song['albumart']?>'>
		</a>
		<audio controls onplay='changebackground("<?php echo "{$song['albumart']}";?>");'><source src='<?php echo $song['songloc']?>'></audio>
		<div class="song-details">
			<h4><?php echo $song['stitle']?></h4>
			<p>
			<?php 
			$rating = find_songs_avg_rating($song['song_id']); 
			echo (int)$rating['avgrating']."/10 by ".$rating['no_users']." users"; 
			?>
			</p>
			<p><?php echo $song['atitle']?></p>
            <p class="sc-name"><?php echo $song['name']?></p>
		</div>
		<div class="song-artist-detail sc-sa-detail">
			<img class="song-artist-pic" src='<?php 
											$loc = $song['picloc']? $song['picloc']: "uploads/defaultpics/rocktheworld.jpg"; 
											echo "$loc";
											?>'>
			<p><?php echo $song['name']?></p>
		</div>
	</div>

<?php 

if(logged_in()&&$song['artist_id']==$_SESSION['user_id'])
	echo"<a class='btn btn-primary' style='margin-left: 5px; padding: 2px;' href='add_song.php?esngid={$song['song_id']}&edit=1&album_id={$song['album_id']}'>Edit</a>";

if(logged_in()&&$song['artist_id']==$_SESSION['user_id'])
	echo"<a class='btn btn-danger' style='margin-left: 5px; padding: 2px;' onClick=\"javascript: return confirm('Please confirm deletion');\" href='delete.php?sngid={$song['song_id']}'>Delete</a>";
?>


<?php
}
?>

<?php
    if($flag==0){
?>

    <h2 class="no-data text-center"><i class="glyphicon glyphicon-exclamation-sign"></i> You currently don't have any songs!</h2>

    <style>
        
    .result-container{
        display: none;
    }

    .container{
        margin: 0 auto;
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
        background: none;
    }
    .no-data{
        display: table-cell;
        vertical-align: middle;
        color: #01FF70;
    }
        .no-data i{
            color: #ffffff;
        }
</style>
<?php
    }
?>
<?php include_once('../layout/footer.php');?>
