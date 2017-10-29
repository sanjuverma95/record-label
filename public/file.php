<?php
$conn = mysqli_connect('localhost', 'root', 'ravi', 'recordlabel') or die();
$result = mysqli_query($conn, 'SELECT uid, name, password from rluser where password="$2y$10$e1p.N/sMRUn81POuJB4A5eOy6eoN86mN7/idtErnTuFz5QEdRgbJa"');

$row = mysqli_fetch_assoc($result);

echo "{$row['uid']} {$row['name']} {$row['password']}";

?>