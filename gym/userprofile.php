<?php
//check if user is signed in
session_start();
$id=$_GET['id'];
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

include 'config.php';
if(isset($_POST['submit'])){
        //check if there's a file
        //echo 'Submit button clicked';
        $userid=$_SESSION['userid'];
        $postdate=date('d-m-Y');
        if ($_FILES['file']['size'] == 0 && $_FILES['file']['error'] == 0){
                //if no file then check post text
                if(empty($_POST['posts'])){
                        //if post is empty then deny submission
                        $error='<img src="images/error.png" width="20" height="20" align="left"> You cannot submit an empty post!';
                        echo '<script>alert('.$error.')</script>';
                }else{
                      $post=addslashes($_POST['posts']);
                      mysqli_query($config,"INSERT INTO posts(userid,post,postdate,likes) VALUES('$userid','$post','$postdate','0')");  
                }
        }else{
                $filename=$_FILES['file']['name'];//picks the file name of the file selected
                $ext = pathinfo($filename, PATHINFO_EXTENSION); //picks the file extension
                            $random=rand(10000,50000);
                            $newfilename='media/file_'.$random.'.'.$ext;
                            move_uploaded_file( $_FILES['file']['tmp_name'],$newfilename );
                if(empty($_POST['posts'])){
                        mysqli_query($config,"INSERT INTO posts(userid,likes) VALUES('$userid','0')");
                        //find the last post and pick its post id
                        $pstqry=mysqli_query($config,"SELECT * FROM posts ORDER BY id DESC limit 1");
                        $pstrow=mysqli_fetch_assoc($pstqry);
                        $postid=$pstrow['id'];
                        mysqli_query($config,"INSERT INTO images(postid,imgname) VALUES('$postid','$newfilename')");
                }
                
        }
}
//load statuses
$statusqry=mysqli_query($config,"SELECT * FROM posts WHERE userid='$id' ORDER BY id DESC");
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
<div class="status" style="border:1px solid pink; ">
<?php
$usrqry=mysqli_query($config,"SELECT * FROM users WHERE id='$id'");
$usrrow=mysqli_fetch_assoc($usrqry);
$usrphoto=$usrrow['photo'];
$names=$usrrow['names'];
$emailaddress=$usrrow['emailaddress'];
$img=$usrrow['photo'];
$from=$usrrow['regdate'];
echo '<img src="'.$img.'" width="170" height="180" style="float:left; border-radius:50%; margin-bottom:8px; margin-top:5px;">';
echo '<div style="float:left; margin-top:5px;"><b>Names: '.$names.'<br>Email: '.$emailaddress.'<br>Member Since: '.$from.'</b></div>';
?>
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
        $likes=$detailrow['likes'];
        echo '<div style="float:left; margin:5px;"><a href="userprofile.php?id='.$userid.'" style="text-decoration:none;"><img src="'.$img.'" width="40" height="40" style="border-radius:50%; float:left;"><div style="float:left; margin-left:5px; margin:5px;">'.$names.'
        </a><div style="font-size:12; color:grey; width:100%;">'.$time.'</div>';
        echo '<div style="margin-top:5px;">'.$status.'</div></div>';
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
        echo '<div><a href="like.php?id='.$postid.'" style="font-size:12;">Like '.$likes.'</a> | <a href="like.php?id='.$postid.'" style="font-size:12;">Comments 0</a> </div>';
        echo $error.'</div></div>';
}
?>

</div>

<?php
include 'styles.html';
?>