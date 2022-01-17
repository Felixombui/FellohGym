<?php
session_start();
include 'config.php';
if(empty($_SESSION['user'])){
    $userdetails='<a href="signin.php" style="color:white; margin-top:10px; margin-left:5px;">Sign in</a> | <a href="signup.php" style="color:white;">Sign up</a> free';
    $profpic='<a href="signin.php" style="color:white;"><img src="images/user.png" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;></a>';
}else{
$userdetails=$_SESSION['user'];
$userphoto=$_SESSION['photo'];
$profpic='<img src="'.$userphoto.'" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;">';
    $postform='<div style="margin-top:10px; border:1px solid pink; box-shadow:2px 2px 2px grey; padding: 15px; border-radius:5px;">'.$profpic.'
    <form method="post" enctype="multipart/form-data"><textarea name="posts" placeholder="What are we doing today..." style="width:90%;"></textarea><div align="right"><input type="file" name="file" style="margin-left:1px; width:80%; float:left;"><input type="submit" name="submit" value="Post" style="float:right: margin-right:15px; width:100px;">
    </div></form>
    
    </div>';
}
$userid=$_SESSION['userid'];
$trgtqry=mysqli_query($config,"SELECT * FROM targets WHERE userid='$userid'");

?>
<div class="header"><img src="images/menu.png" width="30" height="30" align="left"> &nbsp;Gym Trainer & Progress  Tracker
<div style="margin-top: 10px;"><?php echo '<a href="profile.php" style="color:white; text-decoration:none;">'.$profpic.' '.$userdetails.'</a>' ?></div>
<div align="right" class="menutext" ><a href="index.php" style="color: white; text-decoration:none;">Home</a> | <a href="targets.php" style="color: white; text-decoration:none;">Targets</a> | <a href="profile.php" style="color: white; text-decoration:none;">Profile</a></div>
</div>

<div class="fullbody">
    <form method="post">
        <input type="text" name="target" placeholder="Enter your target here..." required="required">
        <input type="text" name="period" placeholder="Enter period e.g. 1 month" required="required">
        <textarea placeholder="Type your initial comments..." name="comments" style="width: 100%; margin-top: 10px; height: 200px;"></textarea>
        <input type="submit" name="set" value="Set Target" style="padding-top: 5px; padding-bottom:5px;">
    </form>
</div>
<?php
if(isset($_POST['set'])){
    $target=addslashes($_POST['target']);
    $targettime=addslashes($_POST['period']);
    $comments=addslashes($_POST['comments']);
    if(mysqli_query($config,"INSERT INTO targets(userid,`target`,targettime,comments) VALUES('$userid','$target','$targettime','$comments')")){
        header('location:targets.php');
    }else{
        echo 'Error! Target setting failed!';
    }
}
?>
<?php
include 'styles.html';
?>