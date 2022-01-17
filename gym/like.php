<?php
include 'config.php';
$postid=$_GET['id'];
$qry=mysqli_query($config,"SELECT * FROM posts WHERE id='$postid'");
$row=mysqli_fetch_assoc($qry);
$likes=$row['likes'];
$totallikes=$likes+1;
mysqli_query($config,"UPDATE posts SET likes='$totallikes' WHERE id='$postid'");
header('location:index.php');

?>