<?php
session_start();
?>
<?php 
require_once('functions.php');
require_once('queryfunctions.php');
$activePage = "index.php";
include_once('../layout/header.php');
require_once('connect_db.php');


?>

<?php 
	if(isset($_GET['success']) && $_GET['success'] == 1){
		echo "<p class='text-center'>Registered Successfully. Please Login</p>";
	} 
	if(isset($_GET['success']) && $_GET['success'] == 2 && logged_in()){
		echo "<p class='text-center'>Logged in Successfully<br> Welcome,{$_SESSION['user_name']}</p>";
	} 
?>

<?php
	if(isset($_GET['submit'])){
		$resultarr = search_by_term($_GET['searchterm']);//has resultset of every table in array
		//find the set and add it to array 0=>artists, 1=>albums, 2=>songs
		//order according to the required query
		$searchtag = $_GET['searchterm'];
		$fgenderm=$fgenderf=$fgenre=$frating=false;

		if(isset($_GET['fgenderm']))
			$fgenderm = $_GET['fgenderm'];	

		if(isset($_GET['fgenderf']))
			$fgenderf = $_GET['fgenderf'];

		if(isset($_GET['fgenre']))
			$fgenre = $_GET['fgenre'];

		if(isset($_GET['frating']))
			$frating = $_GET['frating'];




		if(isset($_GET['orderby']))
			$resultarr[0] = find_users_as_requested($searchtag, $_GET['orderby']);
		else
			$resultarr[0] = find_users_as_requested($searchtag);

		// $resultarr[1] = find_albums_as_requested($searchtag);
		// $resultarr[2] = find_songs_as_requested($searchtag);

		if(isset($_GET['all'])||isset($_GET['artists'])){
			//display all users in resultarr[0]
			echo "<div class='result-container col-md-10 col-md-offset-1'>
					<h4 class='text-center'>Arists</h4>";
			while ($user = mysqli_fetch_assoc($resultarr[0])) {
			?>
				<a href='single_song.php?uid=<?php echo $user['uid']; ?>&reviewdisplay=0'>
                    <div class="song-artist-detail song-artist-detail-search gender<?php echo $user['gender'];?>">
                        <img class="song-artist-pic" src='<?php $loc = $user['picloc']? $user['picloc']: "uploads/defaultpics/rocktheworld.jpg"; echo "$loc";?>'>
                        <p><?php echo $user['name']?><br>
                        <?php 
                            $r = isset($user['turating'])?round((float)$user['turating'],1):0;
                            $nr = isset($user['noofreviews'])?$user['noofreviews']:0;
                            echo "{$r}/10 by {$nr}";
                        ?>
                        </p>	
                    </div>
				</a>
			<?php
			}
			echo "</div>";
		}

		if(isset($_GET['orderby']))
			$resultarr[1] = find_albums_as_requested($searchtag, $_GET['orderby']);
		else
			$resultarr[1] = find_albums_as_requested($searchtag);

		if(isset($_GET['all'])||isset($_GET['albums'])){
			echo "<div class='result-container col-md-10 col-md-offset-1'>
					<h4 class='text-center'>Albums</h4>";
			//display all albums in resultarr[1]
			while ($album = mysqli_fetch_assoc($resultarr[1])) {
				/*echo "$row[$id]";*/
				//set that will contain only one album

				//$album = mysqli_fetch_assoc($album); //utilizing the same variable as it makes sense
			?>
				<div class="album">
					<a href='<?php echo "single_song.php?abmid={$album['album_id']}&reviewdisplay=0"; ?>'>
                        <img class="album-art" src='<?php 
                        $loc = $album['albumart']? $album['albumart']: "uploads/defaultpics/albumart.jpg"; echo $loc; ?>' >
					</a>
					<div class="album-details">
						<h4><b>
						<?php echo $album['title']?>
						</b></h4>
						<p>
							<?php 
								$r = isset($album['arating'])?round((float)$album['arating'],1):0;
								$nr = isset($album['noofreviews'])?$album['noofreviews']:0;
								echo "{$r}/10 by {$nr}";
							?>
						</p>
						<p>
						<?php echo $album['name']?>
						</p>
					</div>
				</div>

			<?php
			}
			echo "</div>";
		}


		if(isset($_GET['orderby']))
			$resultarr[2] = find_songs_as_requested($searchtag, $_GET['orderby']);
		else
			$resultarr[2] = find_songs_as_requested($searchtag);


		if(isset($_GET['all'])||isset($_GET['songs'])){
			echo "<div class='result-container col-md-10 col-md-offset-1'>
					<h4 class='text-center'>Songs</h4>";

			//display all songs in resultarr[2]
			while ($song = mysqli_fetch_assoc($resultarr[2])) {
				$songprev= $song;
			?>
				<br>
				<div class="song genre<?php echo $song['g_id'];?>">
					<a href='single_song.php?sngid=<?php echo "{$song['song_id']}";?>'>
					   <img class="song-art" src='<?php echo "{$song['albumart']}";?>'>
					</a>
					<audio controls onplay='changebackground("<?php echo "{$song['albumart']}";?>");' ><source src='<?php echo $song['songloc']?>'></audio>
					<div class="song-details">
						<h4><?php echo $song['stitle']?></h4>
						<p>
                            <?php 
                            $rating = $song['srating']; 
                            echo round((float)$rating,1)."/10 by ".$song['noofreviews']." users"; 
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
			}
			echo "</div>";
		}
			
	}
	else{ 
?>
<div class="form-container col-md-6 col-md-offset-3">
	<form class='form' method='GET' action='index.php'>
        
		<div class="form-group">
            <label class="control-label">
            <h1>Search</h1>
            <input name='searchterm' type='text' value='' placeholder='Search by name here...'></input>
            </label>
        </div>
        
        <div class="col-md-12 col-md-offset-0">
            <div class="form-group form-set">
                <label class="control-label"><h2>Search For</h2>
                    <div class="checkbox">
                        <label class="control-label"><input type='checkbox' name='all' checked>All  </label>
                        <label class="control-label"><input type='checkbox' name='artists'>Artists  </label>
                        <label class="control-label"><input type='checkbox' name='albums'>Albums  </label>
                        <label class="control-label"><input type='checkbox' name='songs'>Songs  </label>
                    </div>
                </label>
            </div>

            <!--These are order by clauses order by clauses can be only one-->
            <div class="form-group form-set">
                <label class="control-label"><h2>Order By</h2>
                    <label class="control-label"><input type="radio" name="orderby" value="id"> Latest</label>
                    <label class="control-label"><input type="radio" name="orderby"  value="name"> Name</label>
                    <label class="control-label"><input type="radio" name="orderby"  value="rating"> Rating</label>
                </label>
            </div>

            <!--These are where clauses having and can be any combination-->
            <div class="form-group form-set">
                 <label class="control-label"><h2>Filters</h2><hr>

                     <div class="form-group">
                         <label class="control-label"><h3>Gender </h3> 
                             <label class="control-label"><input type="checkbox" name="fgenderm" value="MALE"> Male</label>
                             <label class="control-label"><input type="checkbox" name="fgenderf" value="FEMALE"> Female</label>
                        </label>
                      </div>

                    <div class="form-group">
                        <label class="control-label"><h3>Rating </h3>
                            <select name='frating'>

                                <?php 
                                    $num = 1;
                                    for ($num=0; $num<=10; $num++) {
                                        # code...
                                        echo "<option value='{$num}' style='padding:5px;width=40px;margin:0px auto;'>
                                                &gt={$num}
                                            </option>";
                                    }
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><h3>Genre (applies only for songs) </h3>
                            <select name='fgenre'>
                                    <option class='genre' value='<?php echo false; ?>' >None</option>
                                <?php 
                                    $gquery = "SELECT * FROM GENRE ";

                                    $rows = mysqli_query($conn, $gquery);
                                    while($row=mysqli_fetch_assoc($rows)){
                                        echo "<option class='genre{$row['g_id']}' value='{$row['g_id']}' style='padding:5px;width=40px;margin:0px auto;'>{$row['name']}</option>";
                                    }
                                ?>
                            </select>
                        </label>
                    </div>
                </label>
            </div>
            <div class="form-group">
                <input class="btn btn-success green" type='submit' name='submit' value="Search">
                <br>
            </div>
        </div>
	</form>
</div>

<?php
	}
?>
<?php include_once('../layout/footer.php');?>