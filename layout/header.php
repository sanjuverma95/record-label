<?php
$pages = array();
$pages["index.php"] = "Home";
$pages["albums.php"] = "Albums";
$pages["view_song.php"] = "Songs";
if(!logged_in()){
  $pages["login.php"] = "Login";
  $pages["register.php"] = "Sign Up";
}else{
  $pages["albums.php?uid={$_SESSION['user_id']}"] = "My Album";
  $pages["my_songs.php"] = "My Songs";
  $pages["add_album.php"] = "Add Album";
  $pages["add_song.php"] = "Add Songs"; //change the link to view that displays albums of user to which he wants to add songs
  $pages["img.php?user_id={$_SESSION['user_id']}"]= "Change Pic";
  $pages["logout.php"] = "Logout";
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Record Label</title>
	
	<link rel="stylesheet" href="../public/styles/css/bootstrap.min.css">
	<!-- <script type="text/javascript" src="../public/styles/js/bootstrap.js"></script> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    
    <link rel="icon" type="image/png" sizes="32x32" href="../public/styles/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../public/styles/favicon-16x16.png">
    
    <link rel="stylesheet" href="../public/styles/styles.css">
    
    <script>
        
        
        function changebackground(loc){
            document.getElementById("background-image").style.backgroundImage= "url("+loc+")";
        }
        
        
        document.addEventListener('play', function(e){
    var audios = document.getElementsByTagName('audio');
    for(var i = 0, len = audios.length; i < len;i++){
        if(audios[i] != e.target){
            audios[i].pause();
        }
    }
}, true);
    </script>
</head>
<body>
<div id="background-image"></div>
<div id='wrap'>
<header class='header'>
	 <nav class="navbar navbar-fixed-top black">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php"><span><img src="styles/logo.png"></span> Record Label</a>
	    </div>
	    <div class="collapse navbar-collapse" id="myNavbar">
	      <ul class="nav navbar-nav navbar-right">
	      <?php foreach($pages as $url=>$title){ ?>
			<li>
				<a class='<?php if($activePage===$url){echo "active";}?>' href="<?php echo $url ?>">
					<?php if($url==="register.php") {?><span class="glyphicon glyphicon-user"></span><?php } ?>
					<?php if($url==="login.php") {?><span class="glyphicon glyphicon-log-in"></span><?php } ?>
					<?php echo $title ?>
				</a>
			</li>
		  <?php } ?>
              <li><p class="text-center"style="margin-top: 10px; font-size: 16px; color: #66ff66; padding:5px; font-weight: bold;"><?php if(logged_in())echo "{$_SESSION['user_name']}";?></p></li>
	      </ul>
	    </div>
	  </div>
	</nav>
</header>

<div class="container">

