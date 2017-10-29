<?php
//set $songs variable by id on calling it in another file 
//$songs = select_all_songs_or_by_id($_GET['id']);
if(!(session_status() == PHP_SESSION_ACTIVE))
	session_start();
require_once('connect_db.php');
require_once('queryfunctions.php');
require_once('functions.php');
$songs = select_all_songs_or_by_id();//if no get get all
$activePage = "albums.php";
if(isset($_GET['sngid'])){
	$songs = select_all_songs_or_by_id(false, $_GET['sngid'] );//uid, sid, abid
}
if(isset($_GET['abmid'])){
	$songs = select_all_songs_or_by_id(false, false, $_GET['abmid']);//uid, sid, abid
}
if(isset($_GET['uid'])){
	$songs = select_all_songs_or_by_id($_GET['uid']);//uid, sid, abid
}

include_once('../layout/header.php');
echo"<h2 class='hide text-center'>Songs</h2>";
?>

<?php
$flag=0;
while($song = mysqli_fetch_assoc($songs)){
	// $a = find_album_by_id($song['album_id']);
	$songprev= $song;
    $flag=1;
	?>
<br>
<div class="song genre<?php echo $song['g_id'];?>">
	<a href='add_review.php?sngid=<?php echo "{$song['song_id']}" ?>'>
		<img class="song-art" src='<?php echo $song['albumart']?>'>
	</a>
	<audio controls onplay='changebackground("<?php echo "{$song['albumart']}";?>");'><source src='<?php echo $song['songloc']?>' type="audio/mpeg"></audio>
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
//display reviews
if(!(isset($_GET['reviewdisplay']) && $_GET['reviewdisplay']==0)){
?>
<h3 class='text-center'>Reviews</h3>
<div class='reviewchild'>
<?php
	$reviewSet = find_review_by_id($song['song_id']);
	$prnt="";
	while($rev = mysqli_fetch_assoc($reviewSet)){

		switch ($rev['rating']) {
			case 1:
			case 2:
			case 3: $color='#e74c3c';
					break;
			case 4:
			case 5:
			case 6:
			case 7: $color='#e67e22';
					break;
			case 8:
			case 9:
			case 10: $color='#f1c40f';
					break;
			
			default:
				$color='#95a5a6';
				break;
		}
		$prnt.="<blockquote style='border-left:5px solid {$color};border-bottom:5px solid {$color}; padding:5px;'>
					<h3 style='color:gray;text-align:left;margin-bottom:2px; '>{$rev['name']}</h3>
					<span style='color:{$color};'>{$rev['rating']}/10</span>
					<p>{$rev['review']}</p>
				</blockquote>";

	}
	echo "{$prnt}<br><p class='text-center'>No more reviews</p><hr>";
?></div>
<?php
}
}
?>

<?php
    if($flag==0){
?>

    <h2 class="no-data text-center"><i class="glyphicon glyphicon-exclamation-sign"></i> Currently no songs in this album!</h2>

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
        
        .hide{
            display: none;
        }
</style>
<?php
    }
?>

