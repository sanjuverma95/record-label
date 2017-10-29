<?php 
session_start();
require_once('functions.php');
?>
<?php require_once('queryfunctions.php');
?>
<?php require_once('connect_db.php');?>
<?php 
$activePage = "view_song.php";
include_once('../layout/header.php');
?>

<?php
$songs = select_all_songs_or_by_id();

while($song = mysqli_fetch_assoc($songs)){
	// $a = find_album_by_id($song['album_id']);

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
        <?php $rating = find_songs_avg_rating($song['song_id']); echo round((float)$rating['avgrating'],1)."/10 by ".$rating['no_users']." users"; ?>
        </p>
        <p><?php echo $song['atitle']?></p>
        <p class="sc-name"><?php echo $song['name']?></p>
    </div>
    <div class="song-artist-detail sc-sa-detail text-center">
        <img class="song-artist-pic" src='<?php $loc = $song['picloc']? $song['picloc']: "uploads/defaultpics/rocktheworld.jpg"; echo "$loc";?>'>
        <p><?php echo $song['name']?></p>
    </div>
</div>
<?php
}
?>



<?php include_once('../layout/footer.php');?>