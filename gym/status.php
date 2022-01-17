<?php
//check if user is signed in
session_start();
$id=$_GET['id'];
include 'config.php';
if(empty($_SESSION['user'])){
        $userdetails='<a href="signin.php" style="color:white; margin-top:10px; margin-left:5px;">Sign in</a> | <a href="signup.php" style="color:white;">Sign up</a> free';
        $profpic='<a href="signin.php" style="color:white;"><img src="images/user.png" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;></a>';
}else{
$userdetails=$_SESSION['user'];
$userphoto=$_SESSION['photo'];
$profpic='<img src="'.$userphoto.'" width="30" height="30" align="left" style="border-radius: 50%; margin-top:5px; border:1px solid orange;">';
        $commentform='<div style="margin-top:10px; border:1px solid pink; box-shadow:2px 2px 2px grey; padding: 15px; border-radius:5px;">'.$profpic.'
        <form method="post" enctype="multipart/form-data"><textarea name="comment" placeholder="Add Comment..." style="width:90%;"></textarea><div align="right"><input type="submit" name="submit" value="Comment" style="float:right: margin-right:15px; width:100px;">
        </div></form>
        
        </div>';
}
if(isset($_POST['submit'])){
        //check if there's a file
        //echo 'Submit button clicked';
        $userid=$_SESSION['userid'];
        $postdate=date('d-m-Y');
        $comment=addslashes($_POST['comment']);
        if(mysqli_query($config,"INSERT INTO comments(postid,comment,commentdate) VALUES('$id','$comment','$postdate')")){
                header('location:status.php?id='.$id);
        }
}
//load statuses
$statusqry=mysqli_query($config,"SELECT * FROM posts WHERE id='$id'");
?>
<?php
if($_POST['searchbar']){
        $searchbar=addslashes($_POST['searchbar']);
        $statusqry=mysqli_query($config,"SELECT * FROM posts WHERE post LIKE '%$searchbar%' ORDER BY id DESC");   
}
?>
<div class="header"><img src="images/menu.png" width="30" height="30" align="left"> &nbsp;Gym Trainer & Progress  Tracker
<div style="margin-top: 10px;"><?php echo '<a href="profile.php" style="color:white; text-decoration:none;">'.$profpic.' '.$userdetails.'</a>' ?></div>
<div align="right" class="menutext" ><a href="index.php" style="color: white; text-decoration:none;">Home</a> | <a href="targets.php" style="color: white; text-decoration:none;">Targets</a> | <a href="profile.php" style="color: white; text-decoration:none;">Profile</a>
</div>

</div>
<div>

</div>
<div class="fullbody">
<?php
echo $postform;
while($statusrow=mysqli_fetch_assoc($statusqry)){
        //go through all statuses fetching information.
        echo '<div class="status" style="border:1px solid pink; border-radius:5px;">';
        $userid=$statusrow['userid'];
        $status=$statusrow['post'];
        $time=$statusrow['postdate'];
        $postid=$statusrow['id'];
        $userdetails=mysqli_query($config,"SELECT * FROM users WHERE id='$userid'");
        $detailrow=mysqli_fetch_assoc($userdetails);
        $names=$detailrow['names'];
        $emailaddress=$detailrow['emailaddress'];
        $img=$detailrow['photo'];
        $likes=$statusrow['likes'];
        echo '<div style="float:left; margin:5px;"><a href="userprofile.php?id='.$userid.'" style="text-decoration:none;"><img src="'.$img.'" width="40" height="40" style="border-radius:50%; float:left;"><div style="float:left; margin-left:5px; margin:5px;">'.$names.'
        </a><div style="font-size:12; color:grey; width:100%;">'.$time.'</div>';
        echo '<a href="status.php?id='.$postid.'" style="color:black; text-decoration:none;"><div style="margin-top:5px;">'.$status.'</div></div>';
        //check for media
        $mediaqry=mysqli_query($config,"SELECT * FROM images WHERE postid='$postid'");
        if(mysqli_num_rows($mediaqry)>0){
                $mediarow=mysqli_fetch_assoc($mediaqry);
                $media=$mediarow['imgname'];
                $ext=explode('.',$media);
                $extention=$ext[1];
                if($extention=='png' || $extention=='jpg' || $extention=='jpeg' || $extention=='gif' || $extention=='PNG' || $extention=='JPG' || $ext=='JPEG' || $ext=='GIF' ){
                        echo '<img src="'.$media.'" style="width:100%; height:50%;">';
                }else{
                        echo '<div style="margin-top:5px;"><video width="100%" height="50%" controls>
                        <source src='.$media.' type=video/'.$extention.'></div>';
                }
        }
        $commqry=mysqli_query($config,"SELECT * FROM comments WHERE postid='$postid'");
        $allcomments=0;
        if(mysqli_num_rows($commqry)>0){
                while($comrow=mysqli_fetch_assoc($commqry)){
                        $allcomments=$allcomments+1;
                }
        }else{
                $allcomments=0;
        }
        //Check Comments
        $comqry=mysqli_query($config,"SELECT * FROM comments WHERE postid='$id' ORDER BY id DESC");
        echo '</a><div style="margin-top:8px;"><a href="like.php?id='.$postid.'" style="font-size:12; text-decoration:none;"><img src="images/like.jpg" width="23" height="23" align="left"><b>'.$likes.'</b></a> | <a href="status.php?id='.$postid.'" style="font-size:12; text-decoration:none;"><img src="images/sms.png" width="23" height="23"><b>'.$allcomments.'</b></a> </div>';
        echo $error.'</div>';
        echo $commentform;
        while($comrow=mysqli_fetch_assoc($comqry)){
                $commenttext=$comrow['comment'];
                $comuserid=$comrow['userid'];
                $comtime=$comrow['commentdate'];
                //retrieve user details
                $usrqry=mysqli_query($config,"SELECT * FROM  users WHERE id='$comuserid'");
                $usrrow=mysqli_fetch_assoc($usrqry);
                $usrname=$usrrow['names'];
                echo '<a href="userprofile.php?id='.$comuserid.'" style="text-decoration:none;"><img src="pics/user.png" width="30" height="30" align="left"> '.$usrname.'</a><br><div style="color:grey; font-size:12;">'.$comtime.'</div><div style="margin-left:30px;">'.$commenttext.'</div>';
        }
        '</div>';
}
?>

</div>

<?php
include 'styles.html';
?>