<?php
include 'config.php';
$id=$_GET['id'];
mysqli_query($config,"UPDATE targets SET status='Achieved' WHERE id='$id'");
header('location:viewtarget.php?id='.$id);
?>