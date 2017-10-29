<?php
function find_user($email){
	global $conn;
	$email = mysqli_real_escape_string($conn, $email);
	$query  = "SELECT * FROM RLUSER ";
	$query .= "WHERE email='{$email}' ";
	$query .= "LIMIT 1";

	$user_set = mysqli_query($conn, $query);
	if ($user_set) {
   	 	if($user = mysqli_fetch_assoc($user_set)){
   	 		return $user;
   	 	}else{
   	 		return null;
   	 	}

	} else {
    	return null;
    }
}

function insert_user(){
	global $conn;
	global $name, $email, $password, $age, $gender, $errors;
	$query = "INSERT INTO RLUSER (name, email, password, age, gender) ";
	$query .= "VALUES ('$name', '$email', '$password', $age, '$gender' )";

	$result = mysqli_query($conn, $query);
	if ($result) {
		$id =	mysqli_insert_id ( $conn );
		echo "$id";
   	 	header("Location: img.php?user_id={$id}");
   	 	exit;
	} else {
    	$errors['emailerr']='Looks like email already exists';
	}
}



function attempt_login($email, $password){
	$user = find_user($email);
	if($user){
		//found user check password
		if(password_verify($password, $user['password'])){
			//password matches
			return $user;
		}
		else{
			//password does not match
			return false;
		}

	} else{
		return false;
	}
}

function insert_user_image($target){
	global $conn;

	$result = mysqli_query($conn, "UPDATE rluser SET picloc='{$target}' WHERE uid={$_GET['user_id']}");
	if ($result && !logged_in()) {
   	 	redirect_to("index.php?success=1");
   	}
}

function insert_album(){
	global $conn;
	global $title, $year, $albumart, $errors;
	$query = "INSERT INTO ALBUM (title, artist_id, year) ";
	$query .= "VALUES ('$title', {$_SESSION["user_id"]}, $year)";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$id = mysqli_insert_id ( $conn );
		return $id;
	} else {
    	return null;
	}
}

function insert_album_art($target){
	global $conn, $album_id;

	$result = mysqli_query($conn, "UPDATE ALBUM SET albumart='{$target}' WHERE album_id={$album_id}");
	return $result;
}


function delete_album_by_id($id){
	global $conn;

	$query = "DELETE FROM ALBUM WHERE album_id={$id}";
	$result = mysqli_query($conn, $query);
	return $result;

}

function insert_song(){
	global $conn;
	global $title, $genre_id, $album_id, $errors;
	$title = mysqli_real_escape_string($conn, $title);
	$query = "INSERT INTO SONG (album_id, title, g_id) ";
	$query .= "VALUES ($album_id, '{$title}', $genre_id)";
	$result = mysqli_query($conn, $query);
	if ($result) {
		$id = mysqli_insert_id ( $conn );
		return $id;
	} else {
    	return null;
	}
}



function insert_song_loc($target){
	global $conn, $song_id;

	$result = mysqli_query($conn, "UPDATE song SET songloc='{$target}' WHERE song_id={$song_id}");
	return $result;
}

function update_song($song_id){
	global $conn;


	global $title, $genre_id, $album_id, $errors;

	$result = mysqli_query($conn, "UPDATE song SET title='{$title}', g_id={$genre_id} WHERE song_id={$song_id}");
	return $result;
}

function delete_song_by_id($id){
	global $conn;

	$query = "DELETE FROM SONG WHERE song_id={$id}";
	$result = mysqli_query($conn, $query);
	return $result;

}

function select_all_songs_or_by_id($uid=FALSE,$sid=FALSE,$abid=FALSE){//uid, sid, abid song id or user id or album id or by 
	global $conn;

	$query = "SELECT s.title AS stitle, a.title as atitle, g_id, picloc, s.album_id, albumart, artist_id, year, name, song_id, songloc,email ";
	$query .= "FROM SONG s, ALBUM a, RLUSER u ";
	$query .= "WHERE s.album_id=a.album_id ";
	$query .= "AND a.artist_id = u.uid ";
	if($uid && $sid && $abid){
		$query .= "AND s.SONG_id = {$sid} ";
		$query .= "AND u.uid = {$uid} ";
		$query .= "AND a.album_id = {$abid} ";
	}else if($uid && $abid){
		$query .= "AND a.album_id = {$abid} ";
		$query .= "AND u.uid = {$uid} ";
	}else if($abid && $sid){
		$query .= "AND s.SONG_id = {$sid} ";
		$query .= "AND a.album_id = {$abid} ";
	}else if($uid && $sid){
		$query .= "AND s.SONG_id = {$sid} ";
		$query .= "AND u.uid = {$uid} ";
	}else if($abid){
		$query .= "AND a.album_id = {$abid} ";
	}else if($sid){
		$query .= "AND s.SONG_id = {$sid} ";
	}else if($uid){
		$query .= "AND u.uid = {$uid} ";
	}

	$result = mysqli_query($conn, $query);

	return $result;
}


function select_all_album_or_by_id($u_id=false,$a_id=false){
	global $conn;
	$query  = "SELECT * FROM ALBUM a, RLUSER u, ALBUMRATING ar ";
	$query .= "WHERE u.uid = artist_id and ar.album_id = a.album_id ";
	if($a_id && $u_id){
		$query .= "AND a.album_id={$a_id} ";
		$query .= "AND u.uid={$u_id} ";
	}else if($a_id){
		$query .= "AND a.album_id={$a_id} ";
	}else if($u_id){
		$query .= "AND u.uid={$u_id} ";
	}

	$album_set = mysqli_query($conn, $query);
	if ($album_set) {
   	 	return $album_set;

	} else {
    	return null;
    }
}


function insert_review(){
	global $conn;
	global $song_id, $reviewer_id, $review, $rating;
	$query = "INSERT INTO SONGREVIEW (reviewer_id, song_id, review, rating) ";
	$query .= "VALUES ({$reviewer_id}, {$song_id}, '{$review}', {$rating}) ";
	$result = mysqli_query($conn, $query);
	if ($result) {
		return $result;
	} else {
    	return "An error occured! ".mysqli_error($conn);
	}
}

function find_review_by_id($songid){
	global $conn;
	$query = "SELECT * FROM SONGREVIEW s, RLUSER r ";
	$query .= "WHERE s.song_id={$songid} ";
	$query .= "AND s.reviewer_id=r.uid ";

	$result = mysqli_query($conn, $query);
	return $result;
	
}

function find_songs_avg_rating($songid){
	global $conn;
	$query = "SELECT AVG(rating) as avgrating, count(*) as no_users FROM SONGREVIEW s ";
	$query .= "WHERE s.song_id={$songid} ";

	$result = mysqli_query($conn, $query);
	$rating = mysqli_fetch_assoc($result);
	return $rating;
}



function search_by_term($tags){
	global $conn;
	$tables = ['RLUSER'=> 'name', 'ALBUM' => 'title', 'SONG' => 'title'];
	$tags = explode(' ', $tags);
	$resulta = array();
	foreach ($tables as $table => $attribute) {

		$query = "SELECT * from {$table} ";
		$flag = 1;
		foreach ($tags as $tag) {

			if ($flag) {
				$query .= "WHERE {$attribute} LIKE '%{$tag}%' ";
				$flag = 0; 
				continue;
			}

			$query .= "OR ".$attribute." LIKE '%{$tag}%' ";
		}
		$result = mysqli_query($conn, $query);
		array_push($resulta, $result);

	}
	return $resulta;
}



function find_users_as_requested($tags, $sort=false){
	global $conn, $fgenderm, $fgenderf, $fgenre, $frating;

	$query  = "SELECT * ";
	$query .= "FROM rluser u INNER JOIN userrating ur ON u.uid = ur.uid ";
			$query .= "WHERE u.name LIKE '%{$tags}%' ";

	if($fgenderm&&$fgenderf){

	}else{

		if ($fgenderm) {
			$query .= "AND u.gender = 'MALE' ";
		}
		if ($fgenderf) {
			$query .= "AND u.gender = 'FEMALE' ";
		}
		
	}	
	if ($frating) {
		$query .= "AND ur.turating > {$frating} ";
	}

	if($sort){
		$query.="ORDER BY ";
		switch ($sort) {
			case 'id':
				$query .= "u.uid DESC ";
				break;
			case 'name':
				$query .= "u.name ";
				break;
			case 'rating':
				$query .= "ur.turating DESC ";
				break;
			
			default://will never get to it 
				$query .= "ur.turating DESC ";
				break;
		}
	}
	$resultset = mysqli_query($conn, $query);
	return $resultset;
}


function find_albums_as_requested($tags, $sort=false){
	global $conn, $fgenderm, $fgenderf, $fgenre, $frating;

	$query  = "SELECT * ";
	$query .= "FROM album a INNER JOIN rluser u ON a.artist_id = u.uid ";
	$query .= "INNER JOIN albumrating ar ON a.album_id = ar.album_id ";
	
	$query .= "WHERE a.title LIKE '%{$tags}%' ";

	
	if ($frating) {
		$query .= "AND ar.arating >= {$frating} ";
	}

	if($fgenderm&&$fgenderf){

	}else{

		if ($fgenderm) {
			$query .= "AND u.gender = '{$fgenderm}' ";
		}
		if ($fgenderf) {
			$query .= "AND u.gender = '{$fgenderf}' ";
		}
		
	}

	if($sort){
		$query.="ORDER BY ";
		switch ($sort) {
			case 'id':
				$query .= "a.album_id DESC ";
				break;
			case 'name':
				$query .= "a.title ";
				break;
			case 'rating':
				$query .= "ar.arating DESC ";
				break;
			
			default://will never get to it 
				$query .= "ur.arating DESC ";
				break;
		}
	}
	$resultset = mysqli_query($conn, $query);
	return $resultset;
}


function find_songs_as_requested($tags, $sort=false){
	global $conn, $fgenderm, $fgenderf, $fgenre, $frating;
	//$tags = explode(' ', $tags);

	$query  = "SELECT s.title AS stitle, a.title as atitle, s.g_id, u.picloc, s.album_id, a.albumart, a.artist_id, 
	a.year, u.name, s.song_id, s.songloc, sr.srating, sr.noofreviews ";

	$query .= "FROM song s INNER JOIN album a ON s.album_id = a.album_id ";
	$query .= "INNER JOIN songrating sr ON s.song_id = sr.song_id ";
	$query .= "INNER JOIN rluser u ON a.artist_id = u.uid ";
	// $flag = 1;
	// foreach ($tags as $tag) {

	// 	if ($flag) {
			$query .= "WHERE s.title LIKE '%{$tags}%' ";
	// 		$flag = 0; 
	// 		continue;
	// 	}
	// 	$query .= "OR s.title LIKE '%{$tag}%' ";
	// }
	
	if($fgenderm&&$fgenderf){

	}else{

		if ($fgenderm) {
			$query .= "AND u.gender = '{$fgenderm}' ";
		}
		if ($fgenderf) {
			$query .= "AND u.gender = '{$fgenderf}' ";
		}
		
	}

	if ($fgenre) {
		$query .= "AND s.g_id = {$fgenre} ";
	}
	if ($frating) {
		$query .= "AND sr.srating >= {$frating} ";
	}

	if($sort){
		$query.="ORDER BY ";
		switch ($sort) {
			case 'id':
				$query .= "s.song_id DESC ";
				break;
			case 'name':
				$query .= "s.title ";
				break;
			case 'rating':
				$query .= "sr.srating DESC ";
				break;
			
			default://will never get to it 
				$query .= "ur.arating DESC ";
				break;
		}
	}
	$resultset = mysqli_query($conn, $query);
	return $resultset;
}

?>
