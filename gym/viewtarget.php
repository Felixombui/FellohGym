<?php
session_start();
$targetid=$_GET['id'];
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
$qry=mysqli_query($config,"SELECT * FROM targets WHERE id='$targetid'");

?>
<div class="header"><img src="images/menu.png" width="30" height="30" align="left"> &nbsp;Gym Trainer & Progress  Tracker
<div style="margin-top: 10px;"><?php echo '<a href="profile.php" style="color:white; text-decoration:none;">'.$profpic.' '.$userdetails.'</a>' ?></div>
<div align="right" class="menutext" ><a href="index.php" style="color: white; text-decoration:none;">Home</a> | <a href="targets.php" style="color: white; text-decoration:none;">Targets</a> | <a href="profile.php" style="color: white; text-decoration:none;">Profile</a></div>
</div>

<div class="fullbody" style="margin-top: 10px;">   
<?php
    $row=mysqli_fetch_assoc($qry);
    $target=$row['target'];
    $targettime=$row['targettime'];
    $comments=$row['comments'];
    $date=$row['date_time'];
    $status=$row['status'];
    echo '<div class="status">
    <table width="100%">
    <tr><td><b>Target:</b> '.$target.'</td></tr>
    <tr><td><b>Period:</b> '.$targettime.'</td></tr>
    <tr><td><b>Comments:</b> '.$comments.'</td></tr>
    <tr><td><b>Start Date:</b> '.$date.'</td></tr>
    <tr><td><b>Status:</b> '.$status.'</td></tr>
    <tr><td><b>Update Comments
    <form method="post">
    <textarea style="width:100%; height:120px;" name="comments" placeholder="Update your comments..."></textarea><button name="submit" style="float:right; padding:5px; margin-top:5px;">Update Comment</button>
    </form>
    </td></tr>
    </table>
    
    </div>';
    echo '<a href="setachieved.php?id='.$targetid.'"><input type="submit" name="achieve" value="Set Goal Achieved" style="padding:8px;"></a>';
?>
<?php 
if(isset($_POST['submit'])){
    $comment=addslashes($_POST['comments']);
    if(empty($comment)){
        //do nothing
    }else{
        mysqli_query($config,"UPDATE targets SET comments='$comment' WHERE id='$targetid'");
        echo '<img src="images/success.png" width="23" height="23" align="left"> Comments Updated successfully.';
    }
}
?>
</div>
<?php
include 'styles.html';
?>