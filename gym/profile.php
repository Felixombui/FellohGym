<?php
session_start();
include 'config.php';
if(empty($_SESSION['user'])){
    $userdetails='<a href="signin.php" style="color:white; margin-top:10px; margin-left:5px;">Sign in</a> | <a href="signup.php" style="color:white;">Sign up</a> free';
    $profpic='<a href="signin.php" style="color:white;"><img src="images/user.png" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;></a>';
    header('location:signin.php');
}else{
$userdetails=$_SESSION['user'];
$userphoto=$_SESSION['photo'];
$profpic='<img src="'.$userphoto.'" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;">';
    $postform='<div style="margin-top:10px; border:1px solid pink; box-shadow:2px 2px 2px grey; padding: 15px; border-radius:5px;">'.$profpic.'
    <form method="post" enctype="multipart/form-data"><textarea name="posts" placeholder="What are we doing today..." style="width:90%;"></textarea><div align="right"><input type="file" name="file" style="margin-left:1px; width:80%; float:left;"><input type="submit" name="submit" value="Post" style="float:right: margin-right:15px; width:100px;">
    </div></form>
    
    </div>';
}
?>
<div class="header"><img src="images/menu.png" width="30" height="30" align="left"> &nbsp;Gym Trainer & Progress  Tracker
<div style="margin-top: 10px;"><?php echo '<a href="profile.php" style="color:white; text-decoration:none;">'.$profpic.' '.$userdetails.'</a>' ?></div>
<div align="right" class="menutext" ><a href="index.php" style="color: white; text-decoration:none;">Home</a> | <a href="targets.php" style="color: white; text-decoration:none;">Targets</a> | <a href="profile.php" style="color: white; text-decoration:none;">Profile</a></div>
</div>
<div class="fullbody">
    <div class="status" style="border:1px solid pink;">
       <h2> My Profile </h2>
       <?php echo '<img src="'.$userphoto.'" style="width:180; height:200; margin-bottom: 20px; border:1px solid pink; float:left; border-radius:8px;">' ?>
       <div style="float:left; margin-left:20px;"><?php echo '<b>Names:</b> '. $userdetails ?><br> <?php echo '<b>Email:</b>'. $_SESSION['emailaddress'] ?></div>
       
    </div>
    <div class="status" style="height: 50px;"><a href="logout.php" style="text-decoration:none;"><h3>Sign out</h3></a></div>
</div>
<?php
include 'styles.html';
?>