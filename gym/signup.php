<?php
include 'config.php';
if(isset($_POST['signup'])){
    //prepare inputs
    $emailaddress=$_POST['emailaddress'];
    $fullnames=$_POST['names'];
    $password=$_POST['password'];
    $cpassword=$_POST['retypepassword'];
    $regdate=date('d-m-Y');
    //attempt uploading profile photo
    if(isset($_POST['image'])){
        $filename=$_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            $random=rand(10000,50000);
                            $newfilename='pics/pic_'.$random.'.'.$ext;
                            move_uploaded_file( $_FILES['image']['tmp_name'],$newfilename );
    }else{
        $newfilename='pics/user.png';
    }
    //insert account details
    if($accqry=mysqli_query($config,"INSERT INTO users(emailaddress,names,`password`,regdate,photo) VALUES('$emailaddress','$fullnames','$password','$regdate','$newfilename')")){
        $info= 'Account successfully created. <a href="signin.php">Sign in</a> now?';
    }
}
?>
<div class="header"><img src="images/menu.png" width="30" height="30" align="left"> &nbsp;Gym Trainer & Progress  Tracker
<div><h2>Sign up</h2></div>
</div>
<div style="margin-top: 5px;">
<form method="post" enctype="multipart/form-data">
<input type="email" name="emailaddress" placeholder="Enter your email address" required="required">
<input type="text" name="names" placeholder="Enter your full names" required="required">
<input type="password" name="password" placeholder="Create your password" required="required">
<input type="password" name="retypepassword" placeholder="Confirm your password" required="required">
Choose Profile Picture: <input type="file" name="image">
<input type="submit" name="signup" value="Sign Up">
</form>
<p>
    <?php echo $info ?>
</p>
</div>
<?php
include 'styles.html';
?>