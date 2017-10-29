# record-label
A platform for aspiring musicians to showcase their talent and also listen to other artists.

# Setting Up the database

CREATE DATABASE RECORDLABEL;

CREATE TABLE RLUSER(
	uid INT(5) AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(30) NOT NULL, 
	email VARCHAR(254) UNIQUE NOT NULL, 
	password VARCHAR(100) NOT NULL,
	age INT(3) CHECK (AGE>0),
	gender ENUM('MALE', 'FEMALE'),
	picloc VARCHAR(1024)
)ENGINE=INNODB;

CREATE TABLE ALBUM(
	album_id INT(5) AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(30) NOT NULL,
	artist_id INT(5) NOT NULL,
	year INT(4),
	albumart VARCHAR(1024),
	CONSTRAINT fk_album_rluser 
	FOREIGN KEY (artist_id) REFERENCES RLUSER(uid) 
	ON DELETE CASCADE
)ENGINE=INNODB;

CREATE TABLE GENRE(
	g_id INT(3) AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL UNIQUE KEY
)ENGINE=INNODB;

CREATE TABLE SONG(
	song_id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	album_id INT(5) NOT NULL,
	title VARCHAR(30) NOT NULL,
	g_id INT(3),
	songloc VARCHAR(1024),
	CONSTRAINT fk_song_album
	FOREIGN KEY (album_id) REFERENCES ALBUM(album_id)
	ON DELETE CASCADE,
	CONSTRAINT fk_song_genre
	FOREIGN KEY (g_id) REFERENCES GENRE(g_id)
	ON DELETE SET NULL
)ENGINE=INNODB;

CREATE TABLE SONGREVIEW(
	reviewer_id INT(5) NOT NULL, 
	song_id INT(5) NOT NULL,
	review TEXT NOT NULL,
	rating INT(2) CHECK(rating>=0 AND rating<11),
	PRIMARY KEY (REVIEWER_ID, SONG_ID),
	CONSTRAINT fk_songreview_rluser
	FOREIGN KEY (reviewer_id) REFERENCES RLUSER(uid)
	ON DELETE CASCADE,
	CONSTRAINT fk_songreview_song
	FOREIGN KEY (song_id) REFERENCES SONG(song_id)
	ON DELETE CASCADE
)ENGINE=INNODB;

Create View SongRating As
Select s.album_id, s.song_id, count(sr.song_id) as noofreviews, avg(rating) as srating  ====>total reviews by users in noofreviews and average rating in srating for song
from song s Left Join songreview sr on s.song_id=sr.song_id
group by s.song_id;

create view AlbumRating as
select a.album_id, u.uid, sum(vsr.noofreviews) as noofreviews,avg(vsr.srating) as arating  ====>total reviews by users in noofreviews and average rating in arating for album
from album a 
	Left Join songrating vsr
	on a.album_id = vsr.album_id
	Left Join rluser u
	on u.uid = a.artist_id
group by a.album_id;

Create a view to store avg user rating 
create view UserRating as
select u.uid ,sum(ar.noofreviews) as noofreviews, avg(ar.arating)as turating  ====>total reviews by users in noofreviews and average rating in turating for artist or user
from rluser u left join albumrating ar 
on u.uid=ar.uid
group by u.uid;

# change the max upload size of PHP to 20MB atleast and increase the upload time
