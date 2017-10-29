<?php
session_start();
require_once('functions.php');
//set $album variable by id on calling it in another file 
//$albums = select_all_songs_or_by_id($_GET['id']);
require_once('connect_db.php');
require_once('queryfunctions.php');

$albums = select_all_album_or_by_id();
if(isset($_GET['abmid'])){
	$albums = select_all_album_or_by_id(false, $_GET['abmid']);//uid, abid
}
if(isset($_GET['uid'])){
	$albums = select_all_album_or_by_id($_GET['uid']);//uid, abid
}

$activePage = "albums.php";
if(isset($_GET['uid'])){
    $activePage = "albums.php?uid={$_SESSION['user_id']}";
}
if(isset($_GET['select'])){
    $activePage = "add_song.php";
}
include_once('../layout/header.php');

?>
<div class="result-container col-md-10 col-md-offset-1">

<?php
if(isset($_GET['select'])){
	echo "<h2 class='text-center'>Select an album</h2>";
    $activePage = "add_song.php";
}
$flag=0;
while($album = mysqli_fetch_assoc($albums)){
	// $a = find_album_by_id($song['album_id']);
    $flag=1;
	$albumprev= $album;
?>


<div class="album">
	<a href='<?php 
	if (isset($_GET['select'])&& $_GET['select']==1)
		echo "add_song.php?album_id={$album['album_id']}"; 
	else 
		echo "single_song.php?abmid={$album['album_id']}&reviewdisplay=0"; 
	?>'>
        <img class="album-art" src='<?php 
        $loc = $album['albumart']? $album['albumart']: "uploads/defaultpics/albumart.jpg"; echo $loc;
        ?>' >
	</a>
	<div class="album-details">
		<h4><b>
		<?php echo $album['title']?>
		</b></h4>
		<p>
		<?php echo $album['name']?>
		</p>

		<?php 
		if(logged_in()&&$album['artist_id']==$_SESSION['user_id'])
			echo"<a class='btn btn-danger' style='margin-left: 5px; padding: 2px;' onClick=\"javascript: return confirm('Please confirm deletion');\" href='delete.php?abmid={$album['album_id']}'>Delete</a>";
		else
			echo "<br>";
		?>
	</div>
</div>
<?php
}
?>
</div> 
    
<?php
    $check="";
    if(isset($_GET['select'])){
        $check = "<br><br>Please create an album to add songs";
    }
    if($flag==0){
?>

    <h2 class="no-data text-center"><i class="glyphicon glyphicon-exclamation-sign"></i> You currently don't have any albums!<?php echo $check ?></h2>

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