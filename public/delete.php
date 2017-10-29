<?php
session_start();
require_once('functions.php');
confirm_logged_in();
require_once('connect_db.php');
require_once('queryfunctions.php');
$success = false;
?>
<?php
include_once('queryfunctions.php');
if(isset($_GET['abmid'])){
	$success = delete_album_by_id($_GET['abmid']);
	if($success){
		redirect_to('albums.php?uid='.$_SESSION["user_id"]);
	}	
}
if(isset($_GET['sngid'])){
	$success = delete_song_by_id($_GET['sngid']);//uid, abid
	if($success){
		redirect_to('my_songs.php');
	}
}
?>

